<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Container;

use ShinyTinyCore\Shared\Exception\ShinyTinyReflectionException;
use Throwable;

final class ClassNotFoundException extends ShinyTinyReflectionException
{
    public function __construct(string $class, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: "Target class \"{$class}\" cannot be found", code: $code, previous: $previous);
    }
}
