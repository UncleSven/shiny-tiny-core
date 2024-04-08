<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request;

interface FileFactory
{
    /**
     * @param array<string, string|int> $fileParams
     *
     * @return File
     */
    public function create(array $fileParams): File;
}
