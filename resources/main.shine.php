<?php

declare(strict_types = 1);

use ShinyTinyCore\Shine\Shine;

// This stuff is required by the following sections within this file
Shine::setDefaultSection(
    name   : 'main.title',
    content: config()->getString(key: 'shiny_tiny_framework_name', default: 'Shiny-Tiny Framework'),
);
Shine::setSection(name: 'main.css.reset', content: Shine::minifyCssFile(filename: 'reset.css'));
Shine::setSection(name: 'main.css', content: Shine::minifyCssFile(filename: 'main.css'));
Shine::setViewSection(name: 'main.header', view: 'header/header.shine.php');
?>
<!DOCTYPE html>
<html lang="<?php
Shine::echoLocale(); ?>">
<head>
    <meta charset="<?php
    Shine::echoCharset(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
        Shine::section(name: 'main.title') ?></title>
    <style><?php
        Shine::section(name: 'main.css.reset');
        Shine::section(name: 'main.css');
        ?></style>
</head>
<body>
<header>
    <?php
    Shine::section(name: 'main.header'); ?>
</header>
<main>
    <?php
    Shine::section(name: 'main.content'); ?>
</main>
<footer>
    <br><br><br>
</footer>
</body>
</html>
