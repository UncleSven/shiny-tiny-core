<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Exception;

use Throwable;

final class ShinyTiny404NotFoundException extends ShinyTinyHttpException
{
    public function __construct(string $message = 'Not Found', int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}
