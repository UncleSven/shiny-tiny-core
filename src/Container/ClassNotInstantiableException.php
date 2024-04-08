<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Container;

use ShinyTinyCore\Shared\Exception\ShinyTinyReflectionException;
use Throwable;

final class ClassNotInstantiableException extends ShinyTinyReflectionException
{
    public function __construct(string $class, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: "Target class \"{$class}\" is not instantiable", code: $code, previous: $previous);
    }
}
