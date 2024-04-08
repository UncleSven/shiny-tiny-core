<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

interface HttpCacheHandler
{
    public function get(string $key): ?HttpResponse;

    public function set(string $key, HttpResponse $httpResponse): void;
}
