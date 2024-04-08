<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;
use ShinyTinyCore\Shared\MimeType;

final readonly class TextResponse extends AbstractHttpResponse
{
    /**
     * @param array<string, mixed> $data
     * @param list<string>|null    $headers
     */
    public function __construct(
        public array   $data,
        HttpStatusCode $code = HttpStatusCode::OK,
        ?array         $headers = null,
        TimeToLive     $ttl = TimeToLive::MONTH,
    ) {
        parent::__construct(code: $code, headers: $headers, ttl: $ttl);
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
        return $this->arrayWalk(values: $this->data);
    }

    /**
     * @param array<string, mixed> $values
     */
    private function arrayWalk(array $values, string $indents = ''): string
    {
        $body = '';
        foreach ($values as $key => $value) {
            if (is_scalar(value: $value)) {
                $body .= $indents . $key . ': ' . $value . PHP_EOL;
                continue;
            }

            if (is_array(value: $value)) {
                $body .= $indents . $key . ':' . PHP_EOL;
                $body .= $this->arrayWalk(values: $value, indents: $indents . '    ');
                continue;
            }

            $body .= $indents . $key . ': ' . gettype(value: $value) . PHP_EOL;
        }

        return $body;
    }
}
