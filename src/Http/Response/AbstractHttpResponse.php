<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\HttpResponse;
use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;

abstract readonly class AbstractHttpResponse implements HttpResponse
{
    public string $body;

    public string $charset;

    /**
     * @var list<string>
     */
    public array $headers;

    public string $language;

    public string $locale;

    /**
     * @param list<string>|null $headers
     */
    public function __construct(public HttpStatusCode $code, ?array $headers, public TimeToLive $ttl, ?string $body = null)
    {
        $this->charset  = environment()->getString(key: 'APP_CHARSET', default: 'utf-8');
        $this->language = environment()->getString(key: 'APP_LANGUAGE', default: 'en-US');
        $this->locale   = environment()->getString(key: 'APP_LOCALE', default: 'en');
        $this->body     = $body ?? $this->renderBody();
        $this->headers  = $headers ?? $this->getHeaders();
    }

    public function getExpiryDate(): int
    {
        return $this->ttl->value;
    }

    public function render(): void
    {
        foreach ($this->headers as $header) {
            header(header: $header, response_code: $this->code->value);
        }

        if (environment()->getBool(key: 'APP_DEV_MODE', default: false)) {
            header_remove(name: 'Cache-Control');
        }

        echo $this->body;
    }

    /**
     * @return list<string>
     */
    abstract protected function getHeaders(): array;

    abstract protected function renderBody(): string;
}
