<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use ShinyTinyCore\Shared\Exception\ShinyTinyException;
use ShinyTinyCore\Shared\Exception\ShinyTinyReflectionException;
use Throwable;

final class App
{
    private static self $instance;

    public readonly string $basePath;

    public readonly Config $config;

    public readonly Container $container;

    public readonly string $corePath;

    public readonly Environment $environment;

    public readonly Request $request;

    public readonly Router $router;

    private readonly AppRuntime $appRuntime;

    private readonly ExceptionHandler $exceptionHandler;

    /**
     * @throws ShinyTinyException
     * @throws ShinyTinyReflectionException
     */
    public function __construct(string $basePath)
    {
        self::$instance = $this;

        $appContainer = require __DIR__ . '/Bootstrap/container.php';

        $this->appRuntime       = $appContainer->loadTypeSafe(class: AppRuntime::class);
        $this->exceptionHandler = $appContainer->loadTypeSafe(class: ExceptionHandler::class);

        $this->basePath    = $basePath;
        $this->config      = $appContainer->loadTypeSafe(class: Config::class);
        $this->container   = $appContainer->loadTypeSafe(class: Container::class);
        $this->corePath    = dirname(path: __DIR__, levels: 2);
        $this->environment = $appContainer->loadTypeSafe(class: Environment::class);
        $this->request     = $appContainer->loadTypeSafe(class: Request::class);
        $this->router      = $appContainer->loadTypeSafe(class: Router::class);

        $this->container->bind(abstract: Config::class, concrete: fn() => $this->config);
        $this->container->bind(abstract: Container::class, concrete: fn() => $this->container);
        $this->container->bind(abstract: Environment::class, concrete: fn() => $this->environment);
        $this->container->bind(abstract: Request::class, concrete: fn() => $this->request);
        $this->container->bind(abstract: Router::class, concrete: fn() => $this->router);
    }

    public static function basePath(): string
    {
        return self::$instance->basePath;
    }

    public static function config(): Config
    {
        return self::$instance->config;
    }

    public static function container(): Container
    {
        return self::$instance->container;
    }

    public static function corePath(): string
    {
        return self::$instance->corePath;
    }

    public static function environment(): Environment
    {
        return self::$instance->environment;
    }

    public static function request(): Request
    {
        return self::$instance->request;
    }

    public static function router(): Router
    {
        return self::$instance->router;
    }

    public function run(): HttpResponse
    {
        try {
            // Only determined at runtime, not when the class is instantiated
            $externalExceptionHandler = $this->getExternalExceptionHandler();

            $httpResponse = $this->appRuntime->run();
            $httpResponse->render();
        } catch (Throwable $t) {
            try {
                $exceptionHandler = $externalExceptionHandler ?? $this->exceptionHandler;
                $httpResponse     = $exceptionHandler->handle(throwable: $t);
                $httpResponse->render();
            } catch (Throwable $t) {
                // Fallback, if the external exception handler failed
                $httpResponse = $this->exceptionHandler->handle(throwable: $t);
                $httpResponse->render();
            }
        }

        return $httpResponse;
    }

    /**
     * @throws ShinyTinyException
     * @throws ShinyTinyReflectionException
     */
    private function getExternalExceptionHandler(): ?ExceptionHandler
    {
        if ($this->container->has(abstract: ExceptionHandler::class)) {
            return $this->container->loadTypeSafe(class: ExceptionHandler::class);
        }

        return null;
    }
}
