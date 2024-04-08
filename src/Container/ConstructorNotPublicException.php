<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Container;

use ShinyTinyCore\Shared\Exception\ShinyTinyException;
use Throwable;

final class ConstructorNotPublicException extends ShinyTinyException
{
    public function __construct(
        string $message = 'The class constructor is not public', int $code = 0, ?Throwable $previous = null,
    ) {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}
