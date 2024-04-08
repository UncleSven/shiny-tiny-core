<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use ShinyTinyCore\Shared\Exception\ShinyTiny404NotFoundException;
use ShinyTinyCore\Shared\Http\RequestMethod;

final class Router
{
    /**
     * @var array<string, Route>
     */
    private array $routes = [];

    public function __construct(private readonly Container $container) {}

    /**
     * @param class-string<Controller> $class
     */
    public function defineDelete(string $uri, string $class, string $method): self
    {
        $this->initRoute(requestMethod: RequestMethod::DELETE, uri: $uri, class: $class, method: $method);

        return $this;
    }

    /**
     * @param class-string<Controller> $class
     */
    public function defineGet(string $uri, string $class, string $method): self
    {
        $this->initRoute(requestMethod: RequestMethod::GET, uri: $uri, class: $class, method: $method);

        return $this;
    }

    /**
     * @param class-string<Controller> $class
     */
    public function definePatch(string $uri, string $class, string $method): self
    {
        $this->initRoute(requestMethod: RequestMethod::PATCH, uri: $uri, class: $class, method: $method);

        return $this;
    }

    /**
     * @param class-string<Controller> $class
     */
    public function definePost(string $uri, string $class, string $method): self
    {
        $this->initRoute(requestMethod: RequestMethod::POST, uri: $uri, class: $class, method: $method);

        return $this;
    }

    /**
     * @param class-string<Controller> $class
     */
    public function definePut(string $uri, string $class, string $method): self
    {
        $this->initRoute(requestMethod: RequestMethod::PUT, uri: $uri, class: $class, method: $method);

        return $this;
    }

    public function has(string $uri): bool
    {
        return array_key_exists(key: $uri, array: $this->routes);
    }

    /**
     * @throws ShinyTiny404NotFoundException
     */
    public function load(string $uri): Route
    {
        return $this->routes[$uri] ?? throw new ShinyTiny404NotFoundException();
    }

    /**
     * @param class-string<Controller> $class
     */
    private function initRoute(RequestMethod $requestMethod, string $uri, string $class, string $method): void
    {
        $this->container->bind(abstract: $class, concrete: $class);
        $this->routes[$uri] = new Route(requestMethod: $requestMethod, uri: $uri, controller: $class, method: $method);
    }
}
