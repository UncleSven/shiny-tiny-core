<?php

declare(strict_types = 1);

use ShinyTinyCore\AppRuntime;

$container = new \ShinyTinyCore\Container();

$container->bind(\ShinyTinyCore\AppRuntime::class, AppRuntime::class);
$container->bind(\ShinyTinyCore\Config::class, static fn() => new \ShinyTinyCore\Config(require 'config.php'));
$container->bind(\ShinyTinyCore\Container::class, \ShinyTinyCore\Container::class);
$container->bind(
    \ShinyTinyCore\Environment::class,
    static function (): \ShinyTinyCore\Environment {
        $variables = [];
        $filename  = dirname(__DIR__, 5) . '/.env';
        if (is_file($filename)) {
            $variables = \M1\Env\Parser::parse(file_get_contents($filename) ?: '');
        }

        return new \ShinyTinyCore\Environment($variables);
    },
);
$container->bind(\ShinyTinyCore\ExceptionHandler::class, \ShinyTinyCore\Exception\ExceptionHandler::class);
$container->bind(
    \ShinyTinyCore\HttpCacheHandler::class,
    static function (\ShinyTinyCore\Container $container): \ShinyTinyCore\Http\Cache\CacheHandler {
        return new \ShinyTinyCore\Http\Cache\CacheHandler(
            $container->loadTypeSafe(\ShinyTinyCore\Config::class),
            $container->loadTypeSafe(\ShinyTinyCore\Environment::class),
            [
                DateTimeImmutable::class,
                \ShinyTinyCore\Http\Cache\Item::class,
                \ShinyTinyCore\Http\Response\DownloadResponse::class,
                \ShinyTinyCore\Http\Response\HtmlResponse::class,
                \ShinyTinyCore\Http\Response\JsonResponse::class,
                \ShinyTinyCore\Http\Response\StringResponse::class,
                \ShinyTinyCore\Http\Response\TextResponse::class,
                \ShinyTinyCore\Shared\Http\HttpStatus::class,
                \ShinyTinyCore\Shared\Http\TimeToLive::class,
            ],
            [
                \ShinyTinyCore\Http\Response\FileDownloadResponse::class,
                \ShinyTinyCore\Http\Response\RedirectResponse::class,
            ],
        );
    },
);
$container->bind(\ShinyTinyCore\HttpResponseFactory::class, \ShinyTinyCore\Http\Response\Factory::class);
$container->bind(
    \ShinyTinyCore\Request::class,
    static function (\ShinyTinyCore\Container $container): \ShinyTinyCore\Request {
        return $container
            ->loadTypeSafe(\ShinyTinyCore\Request\RequestFactory::class)
            ->create($_SERVER, $_REQUEST, $_FILES);
    },
);
$container->bind(\ShinyTinyCore\Router::class, \ShinyTinyCore\Router::class);

// Request
$container->bind(\ShinyTinyCore\Request\ContentFactory::class, \ShinyTinyCore\Request\Content\Factory::class);
$container->bind(
    \ShinyTinyCore\Request\FileFactory::class,
    static function (\ShinyTinyCore\Container $container): \ShinyTinyCore\Request\FileFactory {
        return new \ShinyTinyCore\Request\File\Factory(
            $container->loadTypeSafe(\ShinyTinyCore\Shared\ValidateInt::class),
            new \ShinyTinyCore\Shared\ValidateIntOptions(0, null),
            $container->loadTypeSafe(\ShinyTinyCore\Shared\ValidateString::class),
        );
    },
);
$container->bind(\ShinyTinyCore\Request\RequestFactory::class, \ShinyTinyCore\Request\Factory::class);

// Shared
$container->bind(\ShinyTinyCore\Shared\ValidateInt::class, \ShinyTinyCore\Shared\ValidateInt::class);
$container->bind(
    \ShinyTinyCore\Shared\ValidateIntOptions::class,
    static fn() => new \ShinyTinyCore\Shared\ValidateIntOptions(null, null),
);
$container->bind(\ShinyTinyCore\Shared\ValidateString::class, \ShinyTinyCore\Shared\ValidateString::class);
$container->bind(
    \ShinyTinyCore\Shared\ValidateStringNotEmpty::class,
    \ShinyTinyCore\Shared\ValidateStringNotEmpty::class,
);

return $container;
