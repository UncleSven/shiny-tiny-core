<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use ShinyTinyCore\Http\Response\FileDownloadResponse;
use ShinyTinyCore\Http\Response\HtmlResponse;
use ShinyTinyCore\Http\Response\JsonResponse;
use ShinyTinyCore\Http\Response\RedirectResponse;
use ShinyTinyCore\Http\Response\TextResponse;
use ShinyTinyCore\Shared\Http\HttpStatusCode;

interface HttpResponseFactory
{
    public function createFileDownloadResponse(string $filename): FileDownloadResponse;

    /**
     * @param array<string, mixed> $data
     */
    public function createHtmlResponse(array $data, string $view, HttpStatusCode $code): HtmlResponse;

    /**
     * @param array<string, mixed> $data
     */
    public function createJsonResponse(array $data, HttpStatusCode $code): JsonResponse;

    public function createRedirectResponse(string $url): RedirectResponse;

    /**
     * @param array<string, mixed> $data
     */
    public function createTextResponse(array $data, HttpStatusCode $code): TextResponse;
}
