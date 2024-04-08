<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Http;

enum RequestMethod: string
{
    case DELETE = 'delete';
    case GET    = 'get';
    case HEAD   = 'head';
    case PATCH  = 'patch';
    case POST   = 'post';
    case PUT    = 'put';
}
