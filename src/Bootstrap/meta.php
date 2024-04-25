<?php

declare(strict_types = 1);

use ShinyTinyCore\App;
use ShinyTinyCore\Config;
use ShinyTinyCore\Container;
use ShinyTinyCore\Environment;
use ShinyTinyCore\Request;
use ShinyTinyCore\Router;

/**
 * App functionality
 */

if (!function_exists(function: 'basePath')) {
    function basePath(): string { return App::basePath(); }
}

if (!function_exists(function: 'config')) {
    function config(): Config { return App::config(); }
}

if (!function_exists(function: 'container')) {
    function container(): Container { return App::container(); }
}

if (!function_exists(function: 'environment')) {
    function environment(): Environment { return App::environment(); }
}

if (!function_exists(function: 'request')) {
    function request(): Request { return App::request(); }
}

if (!function_exists(function: 'router')) {
    function router(): Router { return App::router(); }
}

/**
 * Helpers
 */

if (!function_exists(function: 'conf')) {
    function conf(string $key, bool|float|int|string|null $default = null): bool|float|int|string|null
    {
        return App::config()->get(key: $key, default: $default);
    }
}

if (!function_exists(function: 'dd') && function_exists(function: 'dump')) {
    function dd(mixed ...$vars): void { dump(...$vars); }
}

if (!function_exists(function: 'env')) {
    function env(string $key, bool|float|int|string|null $default = null): bool|float|int|string|null
    {
        return App::environment()->get(key: $key, default: $default);
    }
}
