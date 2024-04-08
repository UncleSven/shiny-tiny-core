<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;

final readonly class DownloadResponse extends AbstractHttpResponse
{
    /**
     * @param list<string>|null $headers
     */
    public function __construct(
        string         $body,
        public string  $filename,
        HttpStatusCode $code = HttpStatusCode::OK,
        ?array         $headers = null,
        TimeToLive     $ttl = TimeToLive::WEEK,
    ) {
        parent::__construct(code: $code, headers: $headers, ttl: $ttl, body: $body);
    }

    protected function getHeaders(): array
    {
        return [
            'Cache-Control: private, max-age=' . $this->ttl->value,
            'Content-Description: File Transfer',
            'Content-Disposition: attachment; filename="' . $this->filename . '"',
            'Content-Length: ' . strlen(string: $this->body),
            'Content-Type: application/octet-stream',
        ];
    }

    protected function renderBody(): string
    {
        return $this->body;
    }
}
