<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

class Controller
{
    protected readonly string $resources;

    public function __construct()
    {
        $this->resources = config()->getString(key: 'resources_path', default: '');
    }
}
