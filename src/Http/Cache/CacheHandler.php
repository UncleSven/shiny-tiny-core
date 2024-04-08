<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Cache;

use DateTimeImmutable;
use Exception;
use ShinyTinyCore\Config;
use ShinyTinyCore\Environment;
use ShinyTinyCore\Http\PathNotFoundException;
use ShinyTinyCore\HttpCacheHandler;
use ShinyTinyCore\HttpResponse;
use ShinyTinyCore\Shared\Http\TimeToLive;

final readonly class CacheHandler implements HttpCacheHandler
{
    /**
     * @param list<class-string> $allowedClasses
     * @param list<class-string> $excludedResponses
     */
    public function __construct(
        private Config      $config,
        private Environment $environment,
        private array       $allowedClasses,
        private array       $excludedResponses,
    ) {}

    public function get(string $key): ?HttpResponse
    {
        if ($this->isDisabled()) {
            return null;
        }

        $data = @file_get_contents(filename: $this->getFilename(key: $key));
        if ($data === false) {
            return null;
        }

        /** @var Item|false $item */
        $item = unserialize(data: $data, options: ['allowed_classes' => $this->allowedClasses]);
        if ($item === false) {
            throw new CacheException(message: 'Cannot retrieve data from cache (unserialize failed)');
        }

        if ($item->expires < new DateTimeImmutable()) {
            return null;
        }

        return $item->httpResponse;
    }

    /**
     * @throws Exception
     */
    public function set(string $key, HttpResponse $httpResponse): void
    {
        if ($this->isDisabled() || $httpResponse->getExpiryDate() <= TimeToLive::NO_CACHE->value
            || in_array(needle: $httpResponse::class, haystack: $this->excludedResponses, strict: true)) {
            return;
        }

        $time = sprintf('+ %u seconds', $httpResponse->getExpiryDate());
        $item = new Item(expires: new DateTimeImmutable(datetime: $time), httpResponse: $httpResponse);

        $file = $this->getFilename(key: $key);
        $data = file_put_contents(filename: $file, data: serialize(value: $item));
        if ($data === false) {
            throw new CacheException(message: "Cannot write to the target file \"{$file}\"");
        }
    }

    private function getFilename(string $key): string
    {
        return $this->getPath() . '/' . md5(string: $key) . '.txt';
    }

    private function getPath(): string
    {
        $path = $this->config->getString(
            key    : 'cache_path',
            default: $this->config->getString(key: 'shiny_tiny_cache_path', default: ''),
        );

        if (!is_dir(filename: $path)) {
            throw new PathNotFoundException(path: $path);
        }

        return $path;
    }

    private function isDisabled(): bool
    {
        return !$this->environment->getBool(key: 'APP_CACHE', default: false);
    }
}
