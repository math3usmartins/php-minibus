<?php

declare(strict_types=1);

namespace MiniBus\Middleware;

use MiniBus\Envelope;
use MiniBus\Middleware;

final class StackedMiddleware implements Middleware
{
    public function __construct(
        private Middleware $current,
        private ?Middleware $next = null,
    ) {}

    public function handle(
        Envelope $envelope,
        Middleware $next = null,
    ): Envelope {
        return $this->current->handle($envelope, $this->next ?: $next);
    }
}
