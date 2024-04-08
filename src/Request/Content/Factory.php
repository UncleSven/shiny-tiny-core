<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request\Content;

use ShinyTinyCore\Request\Content;
use ShinyTinyCore\Request\ContentFactory;
use ShinyTinyCore\Shared\ValidateStringNotEmpty;

final class Factory implements ContentFactory
{
    private const array IGNORED_CONTENT_TYPES = [
        'application/x-www-form-urlencoded' => true,
        'multipart/form-data'               => true,
    ];

    public function __construct(private readonly ValidateStringNotEmpty $validateStringNotEmpty) {}

    private static function isTypeIgnored(string $type): bool
    {
        return self::IGNORED_CONTENT_TYPES[$type] ?? false;
    }

    public function create(mixed $contentType): ?Content
    {
        $type = $this->validateStringNotEmpty->validate(value: $contentType);
        if ($type === null) {
            return null;
        }

        $type = strtok(string: $type, token: ' ,;') ?: '';
        $type = mb_strtolower(string: $type);
        if (self::isTypeIgnored(type: $type)) {
            return null;
        }

        $body = @file_get_contents(filename: 'php://input');
        if ($body === false) {
            throw new PayloadException();
        }

        return new Content(type: $type, body: $body);
    }
}
