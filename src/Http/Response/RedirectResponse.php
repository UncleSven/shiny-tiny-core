<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;

final readonly class RedirectResponse extends AbstractHttpResponse
{
    /**
     * @param list<string>|null $headers
     */
    public function __construct(
        public string  $url,
        HttpStatusCode $code = HttpStatusCode::MOVED_PERMANENTLY,
        ?array         $headers = null,
        TimeToLive     $ttl = TimeToLive::YEAR,
    ) {
        // Good to know: https://www.more-fire.com/blog/303-307-und-308-weiterleitungen-obacht/
        parent::__construct(code: $code, headers: $headers, ttl: $ttl);
    }

    protected function getHeaders(): array
    {
        return [
            'Cache-Control: private, max-age=' . $this->ttl->value,
            'Location: ' . $this->url,
        ];
    }

    protected function renderBody(): string
    {
        return '';
    }
}
