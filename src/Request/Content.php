<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request;

final readonly class Content
{
    public function __construct(public string $type, public string $body) {}
}
