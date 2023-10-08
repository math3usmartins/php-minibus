<?php

namespace MiniBus\Test\Middleware;

use MiniBus\Envelope;
use MiniBus\Middleware;

final class StubMiddleware implements Middleware
{
    private $handled = false;

    private $callNext;

    public function __construct($callNext = true)
    {
        $this->callNext = $callNext;
    }

    public function handle(Envelope $envelope, Middleware $next = null)
    {
        $this->handled = true;

        if (!$this->callNext) {
            return $envelope;
        }

        return $next
            ? $next->handle($envelope)
            : $envelope;
    }

    public function handled()
    {
        return $this->handled;
    }
}
