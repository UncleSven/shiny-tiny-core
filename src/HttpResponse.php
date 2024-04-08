<?php

declare(strict_types = 1);

namespace ShinyTinyCore;

interface HttpResponse
{
    public function getExpiryDate(): int;

    /**
     * @todo (2024-04-22): Improve cache-control with private/public when authentication is implemented
     * @todo (2024-04-22): Implement security headers
     */
    public function render(): void;
}
