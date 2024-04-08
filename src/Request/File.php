<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request;

final readonly class File
{
    public function __construct(
        public string $name, public string $type, public string $tempName, public int $error, public int $size,
    ) {}
}
