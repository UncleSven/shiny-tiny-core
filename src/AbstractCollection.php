<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

abstract class AbstractCollection
{
    /**
     * @param array<string, scalar> $variables
     */
    public function __construct(protected array $variables) {}

    public function get(string $key, bool|float|int|string|null $default = null): bool|float|int|string|null
    {
        return $this->variables[$key] ?? $default;
    }

    public function getBool(string $key, bool $default): bool
    {
        $value = $this->variables[$key] ?? null;

        return is_bool(value: $value) ? $value : $default;
    }

    public function getFloat(string $key, float $default): float
    {
        $value = $this->variables[$key] ?? null;

        return is_float(value: $value) ? $value : $default;
    }

    public function getInt(string $key, int $default): int
    {
        $value = $this->variables[$key] ?? null;

        return is_int(value: $value) ? $value : $default;
    }

    public function getString(string $key, string $default): string
    {
        $value = $this->variables[$key] ?? null;

        return is_string(value: $value) ? $value : $default;
    }
}
