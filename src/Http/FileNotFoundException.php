<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http;

use ShinyTinyCore\Shared\Exception\ShinyTinyInvalidArgumentException;
use Throwable;

final class FileNotFoundException extends ShinyTinyInvalidArgumentException
{
    public function __construct(string $filename, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: "Target file \"{$filename}\" cannot be found", code: $code, previous: $previous);
    }
}
