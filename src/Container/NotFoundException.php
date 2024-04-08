<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Container;

use ShinyTinyCore\Shared\Exception\ShinyTinyInvalidArgumentException;
use Throwable;

final class NotFoundException extends ShinyTinyInvalidArgumentException
{
    public function __construct(string $abstract, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: "No entry or class found for \"{$abstract}\"", code: $code, previous: $previous);
    }
}
