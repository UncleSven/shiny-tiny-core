<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use JsonException;
use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;
use ShinyTinyCore\Shared\MimeType;

final readonly class JsonResponse extends AbstractHttpResponse
{
    /**
     * @param array<string, mixed> $data
     * @param list<string>|null    $headers
     */
    public function __construct(
        public array   $data,
        HttpStatusCode $code = HttpStatusCode::OK,
        ?array         $headers = null,
        TimeToLive     $ttl = TimeToLive::DAY,
    ) {
        parent::__construct(code: $code, headers: $headers, ttl: $ttl);
    }

    protected function getHeaders(): array
    {
        return [
            'Cache-Control: private, max-age=' . $this->ttl->value,
            'Content-Language: ' . $this->language,
            'Content-Type: ' . MimeType::JSON->value . '; charset=' . $this->charset,
        ];
    }

    /**
     * @throws JsonException
     */
    protected function renderBody(): string
    {
        return json_encode(value: $this->data, flags: JSON_THROW_ON_ERROR) ?: '';
    }
}
