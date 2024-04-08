<?php

declare(strict_types = 1);

use ShinyTinyCore\Http\Response\HtmlResponse;
use ShinyTinyCore\Shine\Shine;

// This stuff is required by the following sections within this file

/** @var HtmlResponse $this */
Shine::init(
    data    : $this->data,
    charset : $this->charset,
    language: $this->language,
    locale  : $this->locale,
    path    : dirname(path: __DIR__) . '/',
);

$title = Shine::data(key: 'code') . ' | ' . Shine::data(key: 'message');
Shine::setSection(name: 'main.title', content: $title);
Shine::captureSection();
?>
    <div id="content_exception_http">
        <h1><?php
            Shine::section(name: 'main.title'); ?></h1>
    </div>
<?php
// This stuff is needed by previous sections
Shine::setCapturedSection(name: 'main.content');
Shine::extendSection(name: 'main.css', content: Shine::minifyCssFile(filename: 'exception/exception_http.css'));
Shine::extend(view: 'main.shine.php');
Shine::close();
