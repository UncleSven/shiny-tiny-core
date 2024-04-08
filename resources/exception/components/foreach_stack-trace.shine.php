<?php

declare(strict_types = 1);

use ShinyTinyCore\Shine\Shine;

/** @var list<array{file: string, line: int, function: string, class: string, type: string}> $data */

$i = count(value: $data);

foreach ($data as $value) { ?>
    <div>
        <table>
            <tr>
                <td>#<?php
                    echo $i; ?></td>
                <td><strong>File:</strong></td>
                <td><?php
                    Shine::echo(value: $value['file']); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><strong>Line:</strong></td>
                <td><?php
                    Shine::echo(value: $value['line']); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><strong>Function:</strong></td>
                <td><?php
                    Shine::echo(value: $value['function']); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><strong>Class:</strong></td>
                <td><?php
                    Shine::echo(value: $value['class']); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><strong>Type:</strong></td>
                <td><?php
                    Shine::echo(value: $value['type']); ?></td>
            </tr>
        </table>
    </div>
    <?php
    $i--;
} // foreach
