<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;
use ShinyTinyCore\Shared\MimeType;

final readonly class StringResponse extends AbstractHttpResponse
{
    /**
     * @param list<string>|null $headers
     */
    public function __construct(
        string         $body,
        HttpStatusCode $code = HttpStatusCode::OK,
        ?array         $headers = null,
        TimeToLive     $ttl = TimeToLive::MONTH,
    ) {
        parent::__construct(code: $code, headers: $headers, ttl: $ttl, body: $body);
    }

    protected function getHeaders(): array
    {
        return [
            'Cache-Control: private, max-age=' . $this->ttl->value,
            'Content-Language: ' . $this->language,
            'Content-Type: ' . MimeType::TXT->value . '; charset=' . $this->charset,
        ];
    }

    protected function renderBody(): string
    {
        return $this->body;
    }
}
