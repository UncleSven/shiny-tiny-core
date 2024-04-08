<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request\Content;

use ShinyTinyCore\Shared\Exception\ShinyTinyRuntimeException;
use Throwable;

final class PayloadException extends ShinyTinyRuntimeException
{
    public function __construct(
        string     $message = 'The payload of "php://input" cannot be read',
        int        $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}
