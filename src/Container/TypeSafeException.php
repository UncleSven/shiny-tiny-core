<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Container;

use ShinyTinyCore\Shared\Exception\ShinyTinyInvalidArgumentException;
use Throwable;

final class TypeSafeException extends ShinyTinyInvalidArgumentException
{
    public function __construct(string $class, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            message : "The given string \"{$class}\" cannot be resolved to object xor the object is not of this class or has not this class as one of its parents",
            code    : $code,
            previous: $previous,
        );
    }
}
