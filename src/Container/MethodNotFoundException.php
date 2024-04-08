<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Container;

use ShinyTinyCore\Shared\Exception\ShinyTinyReflectionException;
use Throwable;

final class MethodNotFoundException extends ShinyTinyReflectionException
{
    public function __construct(string $method, string $class, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            message : "Target method \"{$method}\" for \"{$class}\" cannot be found",
            code    : $code,
            previous: $previous,
        );
    }
}
