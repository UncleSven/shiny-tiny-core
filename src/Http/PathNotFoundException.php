<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http;

use ShinyTinyCore\Shared\Exception\ShinyTinyInvalidArgumentException;
use Throwable;

final class PathNotFoundException extends ShinyTinyInvalidArgumentException
{
    public function __construct(string $path, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: "Target path \"{$path}\" cannot be found", code: $code, previous: $previous);
    }
}
