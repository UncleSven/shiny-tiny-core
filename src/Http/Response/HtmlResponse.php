<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\Http\ViewNotFoundException;
use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;
use ShinyTinyCore\Shared\MimeType;
use Throwable;

final readonly class HtmlResponse extends AbstractHttpResponse
{
    /**
     * @param array<string, mixed> $data
     * @param list<string>|null    $headers
     */
    public function __construct(
        public array   $data,
        public string  $view,
        HttpStatusCode $code = HttpStatusCode::OK,
        ?array         $headers = null,
        TimeToLive     $ttl = TimeToLive::WEEK,
    ) {
        parent::__construct(code: $code, headers: $headers, ttl: $ttl);
    }

    protected function getHeaders(): array
    {
        return [
            'Cache-Control: private, max-age=' . $this->ttl->value,
            'Content-Language: ' . $this->language,
            'Content-Type: ' . MimeType::HTML->value . '; charset=' . $this->charset,
        ];
    }

    protected function renderBody(): string
    {
        if (!is_file(filename: $this->view)) {
            throw new ViewNotFoundException(view: $this->view);
        }

        ob_start();
        try {
            include $this->view;
        } catch (Throwable $t) {
            ob_end_clean();
            throw $t;
        }

        return ob_get_clean() ?: '';
    }
}
