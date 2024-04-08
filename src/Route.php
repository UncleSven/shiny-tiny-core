<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use ShinyTinyCore\Shared\Http\RequestMethod;

final readonly class Route
{
    /**
     * @param class-string<Controller> $controller
     */
    public function __construct(
        public RequestMethod $requestMethod, public string $uri, public string $controller, public string $method,
    ) {}
}
