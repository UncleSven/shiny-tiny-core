<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request;

use ShinyTinyCore\Shared\Exception\ShinyTinyInvalidArgumentException;
use Throwable;

final class InvalidValueException extends ShinyTinyInvalidArgumentException
{
    public function __construct(string $fieldName, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: "The value of \"{$fieldName}\" is invalid", code: $code, previous: $previous);
    }
}
