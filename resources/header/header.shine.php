<?php

declare(strict_types = 1);

use ShinyTinyCore\Shine\Shine;

$name    = config()->get(key: 'shiny_tiny_framework_name', default: 'Shiny-Tiny Framework');
$url     = config()->get(key: 'shiny_tiny_framework_url');
$version = config()->get(key: 'shiny_tiny_framework_version', default: '(not found)');

Shine::extendSection(name: 'main.css', content: Shine::minifyCssFile(filename: 'header/header.css'));
?>
<div id="header">
    <strong><a href="<?php
        Shine::echo(value: $url); ?>"><?php
            Shine::echo(value: $name); ?></a> v<?php
        Shine::echo(value: $version); ?></strong>
</div>
