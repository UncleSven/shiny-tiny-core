<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared;

final class ValidateStringNotEmpty
{
    public function validate(mixed $value): ?string
    {
        if (!is_string(value: $value) || $value === '') {
            return null;
        }

        return $value;
    }
}
