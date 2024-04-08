<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request\File;

use ShinyTinyCore\Request\File;
use ShinyTinyCore\Request\FileFactory;
use ShinyTinyCore\Shared\ValidateInt;
use ShinyTinyCore\Shared\ValidateIntOptions;
use ShinyTinyCore\Shared\ValidateString;

final class Factory implements FileFactory
{
    private static int $counter = 0;

    public function __construct(
        private readonly ValidateInt        $validateInt,
        private readonly ValidateIntOptions $validateIntOptions,
        private readonly ValidateString     $validateString,
    ) {}

    public function create(array $fileParams): File
    {
        $i = self::$counter++;

        $name = $this->validateString->validate(
            value: $fileParams['name'] ?? null,
        ) ?? throw new InvalidValueException(counter: $i, fieldName: 'name');

        $type = $this->validateString->validate(
            value: $fileParams['type'] ?? null,
        ) ?? throw new InvalidValueException(counter: $i, fieldName: 'type');

        $tempName = $this->validateString->validate(
            value: $fileParams['tmp_name'] ?? null,
        ) ?? throw new InvalidValueException(counter: $i, fieldName: 'tmp_name');

        $error = $this->validateInt->validate(
            value  : $fileParams['error'] ?? null,
            options: $this->validateIntOptions,
        ) ?? throw new InvalidValueException(counter: $i, fieldName: 'error');

        $size = $this->validateInt->validate(
            value  : $fileParams['size'] ?? null,
            options: $this->validateIntOptions,
        ) ?? throw new InvalidValueException(counter: $i, fieldName: 'size');

        return new File(name: $name, type: $type, tempName: $tempName, error: $error, size: $size);
    }
}
