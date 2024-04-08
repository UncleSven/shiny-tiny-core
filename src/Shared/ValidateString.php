<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared;

final class ValidateString
{
    public function validate(mixed $value): ?string
    {
        return is_string(value: $value) ? $value : null;
    }
}
