<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Http;

enum TimeToLive: int
{
    case HOUR     = 3600;
    case DAY      = 86400;
    case WEEK     = 604800;
    case MONTH    = 2628000;
    case YEAR     = 31536000;
    case NO_CACHE = 0;
}
