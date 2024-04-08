<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Http\Cache;

use DateTimeImmutable;
use ShinyTinyCore\HttpResponse;

final readonly class Item
{
    public function __construct(public DateTimeImmutable $expires, public HttpResponse $httpResponse) {}
}
