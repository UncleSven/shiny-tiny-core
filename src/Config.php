<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

final class Config extends AbstractCollection
{
    public function __construct()
    {
        parent::__construct(variables: require __DIR__ . '/Bootstrap/config.php');
    }

    public function set(string $key, bool|float|int|string $value): void
    {
        $this->variables[$key] ??= $value;
    }
}
