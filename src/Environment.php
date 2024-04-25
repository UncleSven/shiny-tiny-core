<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

use M1\Env\Parser;

final class Environment extends AbstractCollection
{
    public function __construct()
    {
        $variables = [];
        $filename  = App::basePath() . '/.env';
        if (is_file(filename: $filename)) {
            $variables = Parser::parse(content: file_get_contents(filename: $filename) ?: '');
        }

        parent::__construct(variables: $variables);
    }
}
