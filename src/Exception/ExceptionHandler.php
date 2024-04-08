<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Exception;

use ShinyTinyCore\Config;
use ShinyTinyCore\Environment;
use ShinyTinyCore\ExceptionHandler as ExceptionHandlerInterface;
use ShinyTinyCore\HttpResponse;
use ShinyTinyCore\HttpResponseFactory;
use ShinyTinyCore\Request;
use ShinyTinyCore\Shared\Exception\ShinyTinyHttpException;
use ShinyTinyCore\Shared\Http\HttpStatus;
use ShinyTinyCore\Shared\Http\HttpStatusCode;
use ShinyTinyCore\Shared\MimeType;
use ShinyTinyCore\Shared\TraitThrowableToArray;
use Throwable;

class ExceptionHandler implements ExceptionHandlerInterface
{
    use TraitThrowableToArray;

    public function __construct(
        protected readonly Config              $config,
        protected readonly Environment         $environment,
        protected readonly Request             $request,
        protected readonly HttpResponseFactory $responseFactory,
    ) {}

    public function handle(Throwable $throwable): HttpResponse
    {
        if (is_a(object_or_class: $throwable, class: ShinyTinyHttpException::class)) {
            [$code, $data, $view] = $this->createHttpException(exception: $throwable);
        } else {
            [$code, $data, $view] = $this->createException(throwable: $throwable);
        }

        if ($code === HttpStatusCode::INTERNAL_SERVER_ERROR
            && $this->environment->getBool(key: 'APP_DEBUG', default: false)) {
            [$code, $data, $view] = $this->createNoDebugException();
        }

        return match ($this->request->accept) {
            MimeType::JSON => $this->responseFactory->createJsonResponse(data: $data, code: $code),
            MimeType::TXT  => $this->responseFactory->createTextResponse(data: $data, code: $code),
            default        => $this->responseFactory->createHtmlResponse(data: $data, view: $view, code: $code),
        };
    }

    /**
     * @return array{0: HttpStatusCode, 1: array<string, mixed>, 2: string}
     */
    private function createException(Throwable $throwable): array
    {
        $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $data = $this->throwableToArray(throwable: $throwable) ?? [];
        $view = $this->config->getString(
            key    : 'exception_view_path',
            default: $this->config->getString(key: 'shiny_tiny_exception_view_path', default: ''),
        );

        return [$code, $data, $view];
    }

    /**
     * @return array{0: HttpStatusCode, 1: array<string, mixed>, 2: string}
     */
    private function createHttpException(ShinyTinyHttpException $exception): array
    {
        $code = HttpStatusCode::tryFrom(value: $exception->getCode()) ?? HttpStatusCode::INTERNAL_SERVER_ERROR;
        $data = ['code' => $code->value, 'message' => $exception->getMessage()];
        $view = $this->config->getString(
            key    : 'exception_view_path_http',
            default: $this->config->getString(key: 'shiny_tiny_exception_view_path_http', default: ''),
        );

        return [$code, $data, $view];
    }

    /**
     * @return array{0: HttpStatusCode, 1: array<string, mixed>, 2: string}
     */
    private function createNoDebugException(): array
    {
        $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $data = ['code' => $code->value, 'message' => HttpStatus::CODE_500->value];
        $view = $this->config->getString(
            key    : 'exception_view_path_http',
            default: $this->config->getString(key: 'shiny_tiny_exception_view_path_http', default: ''),
        );

        return [$code, $data, $view];
    }
}
