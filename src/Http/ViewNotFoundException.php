<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http;

use ShinyTinyCore\Shared\Exception\ShinyTinyInvalidArgumentException;
use Throwable;

final class ViewNotFoundException extends ShinyTinyInvalidArgumentException
{
    public function __construct(string $view, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(message: "Target view \"{$view}\" cannot be found", code: $code, previous: $previous);
    }
}
