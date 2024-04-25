<?php

declare(strict_types = 1);

$dirnameCore = dirname(path: __DIR__, levels: 2);

/**
 * @return array<string, scalar>
 */
return [
    'resources_path'                      => basePath() . '/resources',
    'shiny_tiny_cache_path'               => basePath() . '/storage/framework/httpCache',
    'shiny_tiny_core_name'                => 'Shiny-Tiny (Core)',
    'shiny_tiny_core_url'                 => 'https://github.com/UncleSven/shiny-tiny-core',
    'shiny_tiny_core_version'             => '1.0.0',
    'shiny_tiny_exception_view_path'      => $dirnameCore . '/resources/exception/exception.shine.php',
    'shiny_tiny_exception_view_path_http' => $dirnameCore . '/resources/exception/exception_http.shine.php',
    'shiny_tiny_framework_name'           => 'Shiny-Tiny Framework',
    'shiny_tiny_framework_url'            => 'https://github.com/UncleSven/shiny-tiny-framework',
    'shiny_tiny_framework_version'        => '1.0.0',
];
