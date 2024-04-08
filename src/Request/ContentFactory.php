<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request;

interface ContentFactory
{
    public function create(mixed $contentType): ?Content;
}
