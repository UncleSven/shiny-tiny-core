<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Http;

enum HttpStatusCode: int
{
    case OK                    = 200;
    case CREATED               = 201;
    case ACCEPTED              = 202;
    case NO_CONTENT            = 204;
    case MOVED_PERMANENTLY     = 301;
    case FOUND                 = 302;
    case SEE_OTHER             = 303;
    case TEMPORARY_REDIRECT    = 307; // http 1.1
    case PERMANENT_REDIRECT    = 308; // http 1.1
    case BAD_REQUEST           = 400;
    case UNAUTHORIZED          = 401;
    case FORBIDDEN             = 403;
    case NOT_FOUND             = 404;
    case METHOD_NOT_ALLOWED    = 405;
    case GONE                  = 410;
    case LOCKED                = 423;
    case INTERNAL_SERVER_ERROR = 500;
}
