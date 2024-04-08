<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Exception;

use Throwable;

final class ChainIsBrokenException extends ShinyTinyException
{
    public function __construct(string $message = 'Chain is broken', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
    }
}
