<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Exception;

use Throwable;

final class ShinyTiny405MethodNotAllowedException extends ShinyTinyHttpException
{
    public function __construct(string $message = 'Method Not Allowed', int $code = 405, ?Throwable $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}
