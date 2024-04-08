<?php

declare(strict_types = 1);

use ShinyTinyCore\Http\Response\HtmlResponse;
use ShinyTinyCore\Shine\Shine;

/** @var HtmlResponse $this */
Shine::init(
    data    : $this->data,
    charset : $this->charset,
    language: $this->language,
    locale  : $this->locale,
    path    : dirname(path: __DIR__) . '/',
);
Shine::setViewSection(
    name: 'exception.stack_trace',
    view: 'exception/components/foreach_stack-trace.shine.php',
    data: Shine::rawData(key: 'trace'),
);
if (Shine::rawData(key: 'previous') === null) {
    Shine::setSection(
        name   : 'exception.previous',
        content: '-',
    );
} else {
    Shine::setViewSection(
        name: 'exception.previous',
        view: 'exception/components/recursive_previous-exception.shine.php',
        data: Shine::rawData(key: 'previous'),
    );
}
Shine::captureSection();
?>
    <div id="content_exception">
        <div class="container">
            <h1>Exception</h1>
            <br>
            <div>
                <table>
                    <tr>
                        <td><strong>Code:</strong></td>
                        <td><?php
                            Shine::echoData(key: 'code'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td><?php
                            Shine::echoData(key: 'type'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Message:</strong></td>
                        <td><?php
                            Shine::echoData(key: 'message'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>File:</strong></td>
                        <td><?php
                            Shine::echoData(key: 'file'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Line:</strong></td>
                        <td><?php
                            Shine::echoData(key: 'line'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="container">
            <h2>Stack Trace</h2>
            <br>
            <div id="exception_stack_trace">
                <?php
                Shine::section(name: 'exception.stack_trace'); ?>
            </div>
        </div>
        <div class="container">
            <h2>Previous Exception</h2>
            <br>
            <div id="exception_previous">
                <?php
                Shine::section(name: 'exception.previous'); ?>
            </div>
        </div>
    </div>
<?php
Shine::setCapturedSection(name: 'main.content');
Shine::extendSection(name: 'main.css', content: Shine::minifyCssFile(filename: 'exception/exception.css'));
Shine::extend(view: 'main.shine.php');
Shine::close();
