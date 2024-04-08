<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request\File;

use ShinyTinyCore\Shared\Exception\ShinyTinyInvalidArgumentException;
use Throwable;

final class InvalidValueException extends ShinyTinyInvalidArgumentException
{
    public function __construct(int $counter, string $fieldName, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            message : "The value of \"{$fieldName}\" from file #{$counter} is invalid",
            code    : $code,
            previous: $previous,
        );
    }
}
