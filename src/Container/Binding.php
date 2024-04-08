<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Container;

use Closure;

final readonly class Binding
{
    /**
     * @param class-string|Closure $concrete
     */
    public function __construct(public string|Closure $concrete, public bool $singleton = true) {}
}
