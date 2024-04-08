<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use ShinyTinyCore\Shared\Exception\ShinyTiny403ForbiddenException;
use ShinyTinyCore\Shared\Exception\ShinyTiny405MethodNotAllowedException;
use ShinyTinyCore\Shared\Exception\ShinyTinyException;
use ShinyTinyCore\Shared\Exception\ShinyTinyHttpException;
use ShinyTinyCore\Shared\Exception\ShinyTinyReflectionException;

final readonly class AppRuntime
{
    public function __construct(
        private Container        $container,
        private HttpCacheHandler $httpCache,
        private Request          $request,
        private Router           $router,
    ) {}

    /**
     * @throws ShinyTinyException
     * @throws ShinyTinyHttpException
     * @throws ShinyTinyReflectionException
     */
    public function run(): HttpResponse
    {
        $route = $this->router->load(uri: $this->request->uri);
        if ($route->requestMethod !== $this->request->method) {
            throw new ShinyTiny405MethodNotAllowedException();
        }

        /** @var Controller $controller */
        $controller = $this->container->loadTypeSafe(class: $route->controller);

        if (method_exists(object_or_class: $controller, method: 'isLocked')) {
            !$controller->isLocked(
                ...$this->container->resolveMethodParameters(class: $controller::class, method: 'isLocked'),
            ) ?: throw new ShinyTiny403ForbiddenException();
        }

        $httpResponse = $this->httpCache->get(key: $this->request->uri);
        if ($httpResponse !== null) {
            return $httpResponse;
        }

        $params       = $this->container->resolveMethodParameters(class: $controller::class, method: $route->method);
        $httpResponse = $controller->{$route->method}(...$params);
        $this->httpCache->set(key: $this->request->uri, httpResponse: $httpResponse);

        return $httpResponse;
    }
}
