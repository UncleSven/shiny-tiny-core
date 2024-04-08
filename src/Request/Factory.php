<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Request;

use ShinyTinyCore\Request;
use ShinyTinyCore\Shared\Http\RequestMethod;
use ShinyTinyCore\Shared\MimeType;
use ShinyTinyCore\Shared\ValidateString;
use ShinyTinyCore\Shared\ValidateStringNotEmpty;

final readonly class Factory implements RequestFactory
{
    public function __construct(
        private ContentFactory         $contentFactory,
        private FileFactory            $fileFactory,
        private ValidateString         $validateString,
        private ValidateStringNotEmpty $validateStringNotEmpty,
    ) {}

    public function create(array $server, array $params, array $files): Request
    {
        $accept = null;
        $string = $this->validateString->validate(value: $_SERVER['HTTP_ACCEPT'] ?? null) ?? '';
        $token  = strtok(string: $string, token: ' ,;');
        while ($token !== false) {
            $accept = MimeType::tryFrom(value: mb_strtolower(string: $token));
            if ($accept !== null) {
                break;
            }
            $token = strtok(string: ' ,;');
        }

        $method = $this->validateString->validate(value: $server['REQUEST_METHOD'] ?? null) ?? '';
        $method = RequestMethod::tryFrom(
            value: mb_strtolower(string: $method),
        ) ?? throw new InvalidValueException(fieldName: 'REQUEST_METHOD');

        $uri = $this->validateStringNotEmpty->validate(
            value: strtok(string: $server['REQUEST_URI'] ?? '', token: '?'),
        ) ?? throw new InvalidValueException(fieldName: 'REQUEST_URI');

        $requestFiles = [];
        foreach ($files as $key => $fileParams) {
            $requestFiles[$key] = $this->fileFactory->create(fileParams: $fileParams);
        }

        $content = $this->contentFactory->create(contentType: $server['CONTENT_TYPE'] ?? null);

        return new Request(
            accept : $accept,
            method : $method,
            uri    : $uri,
            params : $params,
            files  : $requestFiles,
            content: $content,
        );
    }
}
