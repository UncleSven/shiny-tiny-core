<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared;

trait TraitArrayFilterNullValues
{
    /**
     * @template Key
     * @template Value
     *
     * @param array<Key, Value> $arr
     *
     * @return array<Key, Value>
     */
    private function arrayFilterNullValues(array $arr): array
    {
        return array_filter(array: $arr, callback: static fn(mixed $element) => null !== $element);
    }
}
