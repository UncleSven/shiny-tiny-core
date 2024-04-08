<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ShinyTinyCore\Container\Binding;
use ShinyTinyCore\Container\ClassNotFoundException;
use ShinyTinyCore\Container\ClassNotInstantiableException;
use ShinyTinyCore\Container\ConstructorNotPublicException;
use ShinyTinyCore\Container\MethodNotFoundException;
use ShinyTinyCore\Container\NotFoundException;
use ShinyTinyCore\Container\ResolveParameterException;
use ShinyTinyCore\Container\TypeSafeException;

final class Container
{
    /**
     * @var array<string, Binding>
     */
    private array $bindings = [];

    /**
     * @var array<string, object|mixed>
     */
    private array $resolvedBindings = [];

    /**
     * @param class-string|string  $abstract
     * @param class-string|Closure $concrete
     */
    public function bind(string $abstract, string|Closure $concrete, bool $singleton = true): void
    {
        $this->bindings[$abstract] = new Binding(concrete: $concrete, singleton: $singleton);
    }

    public function has(string $abstract): bool
    {
        return array_key_exists(key: $abstract, array: $this->resolvedBindings)
            || array_key_exists(key: $abstract, array: $this->bindings);
    }

    /**
     * @template T of class-string|string
     * @param T $abstract
     *
     * @return (T is class-string ? object : mixed)
     *
     * @throws ClassNotFoundException
     * @throws ClassNotInstantiableException
     * @throws ConstructorNotPublicException
     * @throws ResolveParameterException
     */
    public function load(string $abstract): mixed
    {
        if (array_key_exists(key: $abstract, array: $this->resolvedBindings)) {
            return $this->resolvedBindings[$abstract];
        }

        $binding = $this->bindings[$abstract] ?? throw new NotFoundException(abstract: $abstract);

        if (is_string(value: $binding->concrete)) {
            $resolvedBinding = $this->instantiate(class: $binding->concrete);
        } else {
            $resolvedBinding = $binding->concrete;
            $resolvedBinding = $resolvedBinding($this);
        }

        if ($binding->singleton) {
            $this->resolvedBindings[$abstract] = $resolvedBinding;
            unset($this->bindings[$abstract]);
        }

        return $resolvedBinding;
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     *
     * @return T
     *
     * @throws ClassNotFoundException
     * @throws ClassNotInstantiableException
     * @throws ConstructorNotPublicException
     * @throws ResolveParameterException
     */
    public function loadTypeSafe(string $class)
    {
        $element = $this->load(abstract: $class);
        if (is_object(value: $element) && is_a(object_or_class: $element, class: $class)) {
            return $element;
        }

        throw new TypeSafeException(class: $class);
    }

    /**
     * @param class-string $class
     *
     * @return list<mixed>
     *
     * @throws ClassNotFoundException
     * @throws ClassNotInstantiableException
     * @throws ConstructorNotPublicException
     * @throws MethodNotFoundException
     * @throws ResolveParameterException
     */
    public function resolveMethodParameters(string $class, string $method): array
    {
        $reflector = $this->initReflector(class: $class);

        try {
            $methodName = $reflector->getMethod(name: $method);
        } catch (ReflectionException $e) {
            throw new MethodNotFoundException(method: $method, class: $class, previous: $e);
        }

        return $this->resolveParameters(parameters: $methodName->getParameters());
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     *
     * @return ReflectionClass<T>
     * @throws ClassNotFoundException
     */
    private function initReflector(string $class): ReflectionClass
    {
        try {
            return new ReflectionClass(objectOrClass: $class);
            /** @phpstan-ignore-next-line */
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException(class: $class, previous: $e);
        }
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     *
     * @return T
     *
     * @throws ClassNotFoundException
     * @throws ClassNotInstantiableException
     * @throws ConstructorNotPublicException
     * @throws ResolveParameterException
     */
    private function instantiate(string $class)
    {
        $reflector = $this->initReflector(class: $class);

        // Such as an Interface or Abstract Class
        $reflector->isInstantiable() ?: throw new ClassNotInstantiableException(class: $class);

        // No constructor means no dependencies
        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $class;
        }

        // A ReflectionException if the class constructor is not public
        // A ReflectionException if the class does not have a constructor and the args parameter contains one or more parameters
        try {
            return $reflector->newInstanceArgs(
                args: $this->resolveParameters(parameters: $constructor->getParameters()),
            );
        } catch (ReflectionException $e) {
            throw new ConstructorNotPublicException(previous: $e);
        }
    }

    /**
     * @param list<ReflectionParameter> $parameters
     *
     * @return list<mixed>
     *
     * @throws ClassNotFoundException
     * @throws ClassNotInstantiableException
     * @throws ConstructorNotPublicException
     * @throws ResolveParameterException
     *
     * @todo (2024-04-22): Maybe implement variadic parameters
     */
    private function resolveParameters(array $parameters): array
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            if ($parameter->isOptional()) {
                continue;
            }

            $type = $parameter->getType();

            if ($type === null) {
                throw new ResolveParameterException(
                    message: 'The parameter type is not specified and therefore cannot be resolved',
                );
            }

            if ($type::class !== ReflectionNamedType::class) {
                throw new ResolveParameterException(
                    message: 'Union/Intersection types without a default value cannot be resolved (try manual resolution with Closure)',
                );
            }

            if ($type->isBuiltin()) {
                throw new ResolveParameterException(
                    message: 'Built-in types without a default value cannot be resolved',
                );
            }

            $dependencies[] = $this->load(abstract: $type->getName());
        }

        return $dependencies;
    }
}
