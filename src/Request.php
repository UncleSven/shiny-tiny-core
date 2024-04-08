<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use JsonException;
use ShinyTinyCore\Request\Content;
use ShinyTinyCore\Request\File;
use ShinyTinyCore\Shared\Http\RequestMethod;
use ShinyTinyCore\Shared\MimeType;

final readonly class Request
{
    /**
     * @param array<string, mixed> $params
     * @param array<string, File>  $files
     */
    public function __construct(
        public ?MimeType     $accept,
        public RequestMethod $method,
        public string        $uri,
        public array         $params,
        public array         $files,
        public ?Content      $content,
    ) {}

    /**
     * @param positive-int $depth
     *
     * @return array<string, mixed>|null
     * @throws JsonException
     */
    public function getJsonDecodedContent(int $depth = 512): ?array
    {
        if ($this->content === null) {
            return null;
        }

        $result = json_decode(
            json       : $this->content->body,
            associative: true,
            depth      : $depth,
            flags      : JSON_THROW_ON_ERROR,
        );

        return is_array(value: $result) ? $result : [$result];
    }
}
