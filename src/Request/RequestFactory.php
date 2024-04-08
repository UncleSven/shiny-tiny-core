<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request;

use ShinyTinyCore\Request;

interface RequestFactory
{
    /**
     * @param array<string, string>                    $server
     * @param array<string, mixed>                     $params
     * @param array<string, array<string, string|int>> $files
     */
    public function create(array $server, array $params, array $files): Request;
}
