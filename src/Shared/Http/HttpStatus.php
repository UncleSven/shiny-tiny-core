<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared\Http;

enum HttpStatus: string
{
    case  CODE_200 = 'OK';
    case  CODE_201 = 'Created';
    case  CODE_202 = 'Accepted';
    case  CODE_204 = 'No Content';
    case  CODE_301 = 'Moved Permanently';
    case  CODE_302 = 'Found';
    case  CODE_303 = 'See Other';
    case  CODE_307 = 'Temporary Redirect';
    case  CODE_308 = 'Permanent Redirect';
    case  CODE_400 = 'Bad Request';
    case  CODE_401 = 'Unauthorized';
    case  CODE_403 = 'Forbidden';
    case  CODE_404 = 'Not Found';
    case  CODE_405 = 'Method Not Allowed';
    case  CODE_410 = 'Gone';
    case  CODE_500 = 'Internal Server Error';
}
