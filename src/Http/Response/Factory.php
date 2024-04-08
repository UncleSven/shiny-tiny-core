<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Response;

use ShinyTinyCore\HttpResponseFactory;
use ShinyTinyCore\Shared\Http\HttpStatusCode;

final class Factory implements HttpResponseFactory
{
    public function createDownloadResponse(string $body, string $filename): DownloadResponse
    {
        return new DownloadResponse(body: $body, filename: $filename);
    }

    public function createFileDownloadResponse(string $filename): FileDownloadResponse
    {
        return new FileDownloadResponse(filename: $filename);
    }

    public function createHtmlResponse(array $data, string $view, HttpStatusCode $code): HtmlResponse
    {
        return new HtmlResponse(data: $data, view: $view, code: $code);
    }

    public function createJsonResponse(array $data, HttpStatusCode $code): JsonResponse
    {
        return new JsonResponse(data: $data, code: $code);
    }

    public function createRedirectResponse(string $url): RedirectResponse
    {
        return new RedirectResponse(url: $url);
    }

    public function createStringResponse(string $body): StringResponse
    {
        return new StringResponse(body: $body);
    }

    public function createTextResponse(array $data, HttpStatusCode $code): TextResponse
    {
        return new TextResponse(data: $data, code: $code);
    }
}
