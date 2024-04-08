<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use Throwable;

interface ExceptionHandler
{
    public function handle(Throwable $throwable): HttpResponse;
}
