<?php

declare(strict_types=1);

namespace MiniBus\Test\Middleware;

use MiniBus\Envelope;
use MiniBus\Middleware;

final class StubMiddleware implements Middleware
{
    private bool $handled = false;

    public function __construct(private bool $callNext = true) {}

    public function handle(Envelope $envelope, Middleware $next = null): Envelope
    {
        $this->handled = true;

        if (!$this->callNext) {
            return $envelope;
        }

        return $next
            ? $next->handle($envelope)
            : $envelope;
    }

    public function handled(): bool
    {
        return $this->handled;
    }
}
