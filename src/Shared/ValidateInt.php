<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared;

final class ValidateInt
{
    public function validate(mixed $value, ValidateIntOptions $options): ?int
    {
        $value = filter_var(value: $value, filter: FILTER_VALIDATE_INT, options: $options->toArray());

        return $value !== false ? $value : null;
    }
}
