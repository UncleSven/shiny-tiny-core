<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\Http\FileNotFoundException;
use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\Http\TimeToLive;

final readonly class FileDownloadResponse extends AbstractHttpResponse
{

    /**
     * @param list<string>|null $headers
     */
    public function __construct(
        public string  $filename,
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
            'Content-Description: File Transfer',
            'Content-Disposition: attachment; filename="' . basename(path: $this->filename) . '"',
            'Content-Length: ' . filesize(filename: $this->filename),
            'Content-Type: application/octet-stream',
        ];
    }

    protected function renderBody(): string
    {
        if (!is_file(filename: $this->filename)) {
            throw new FileNotFoundException(filename: $this->filename);
        }

        return file_get_contents(filename: $this->filename) ?: '';
    }
}
