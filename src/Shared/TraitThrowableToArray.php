<?php

declare(strict_types = 1);

namespace ShinyTinyCore\Shared;

use Throwable;

trait TraitThrowableToArray
{
    use TraitArrayFilterNullValues;

    /**
     * @return array<string, mixed>|null
     */
    private function throwableToArray(?Throwable $throwable): ?array
    {
        return null === $throwable ? null : [
            'code'     => $throwable->getCode(),
            'file'     => $throwable->getFile(),
            'line'     => $throwable->getLine(),
            'message'  => $throwable->getMessage(),
            'previous' => $this->throwableToArray(throwable: $throwable->getPrevious()),
            'trace'    => $throwable->getTrace(),
            'type'     => $throwable::class,
        ];
    }
}
