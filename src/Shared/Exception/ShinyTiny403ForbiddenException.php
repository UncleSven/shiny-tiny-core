<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Exception;

use Throwable;

final class ShinyTiny403ForbiddenException extends ShinyTinyHttpException
{
    public function __construct(string $message = 'Forbidden', int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}
