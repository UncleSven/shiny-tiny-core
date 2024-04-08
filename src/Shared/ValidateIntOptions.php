<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared;

final class ValidateIntOptions
{
    use TraitArrayFilterNullValues;

    public function __construct(private readonly ?int $minRange, private readonly ?int $maxRange) {}

    /**
     * @return array<string, int|null>
     */
    public function toArray(): array
    {
        return $this->arrayFilterNullValues(
            arr: [
                     'min_range' => $this->minRange,
                     'max_range' => $this->maxRange,
                 ],
        );
    }
}
