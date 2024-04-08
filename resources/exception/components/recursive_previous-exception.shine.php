<?php

declare(strict_types = 1);

use ShinyTinyCore\Shine\Shine;

$data['counter'] = ($data['counter'] ?? 0) + 1;

/**
 * @var array{
 *     code: int,
 *     file: string,
 *     line: int,
 *  message: string,
 * previous: array<string, mixed>|null,
 *    trace: list<array<string, string|int>>,
 *     type: string,
 *  counter: int} $data
 */

Shine::setViewSection(
    name: 'exception.stack_trace' . $data['counter'],
    view: 'exception/components/foreach_stack-trace.shine.php',
    data: $data['trace'],
);

if ($data['previous'] === null) {
    Shine::setSection(
        name   : 'exception.previous_exception' . $data['counter'],
        content: '-',
    );
} else {
    Shine::setViewSection(
        name: 'exception.previous_exception' . $data['counter'],
        view: 'exception/components/recursive_previous-exception.shine.php',
        data: $data['previous'] + ['counter' => $data['counter']],
    );
}
?>
<div>
    <table>
        <tr>
            <td><strong>Code:</strong></td>
            <td><?php
                Shine::echo($data['code']); ?></td>
        </tr>
        <tr>
            <td><strong>Type:</strong></td>
            <td><?php
                Shine::echo($data['type']); ?></td>
        </tr>
        <tr>
            <td><strong>Message:</strong></td>
            <td><?php
                Shine::echo($data['message']); ?></td>
        </tr>
        <tr>
            <td><strong>File:</strong></td>
            <td><?php
                Shine::echo($data['file']); ?></td>
        </tr>
        <tr>
            <td><strong>Line:</strong></td>
            <td><?php
                Shine::echo($data['line']); ?></td>
        </tr>
        <tr>
            <td><strong>Trace:</strong></td>
            <td>
                <details>
                    <summary>Show Trace</summary>
                    <br>
                    <?php
                    Shine::section(name: 'exception.stack_trace' . $data['counter']); ?>
                </details>
            </td>
        </tr>
        <tr>
            <td><strong>Previous:</strong></td>
            <td>
                <details>
                    <summary>Show Previous</summary>
                    <br>
                    <?php
                    Shine::section(name: 'exception.previous_exception' . $data['counter']); ?>
                </details>
            </td>
        </tr>
    </table>
</div>
