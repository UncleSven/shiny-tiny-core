<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared;

enum MimeType: string
{
    case HTML = 'text/html';
    case JSON = 'application/json';
    case TXT  = 'text/plain';
    case XML  = 'application/xml';
}
