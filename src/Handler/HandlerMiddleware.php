<?php

declare(strict_types=1);

namespace MiniBus\Handler;

use MiniBus\Envelope;
use MiniBus\Middleware;

final class HandlerMiddleware implements Middleware
{
    public function __construct(private HandlerLocator $locator) {}

    public function handle(
        Envelope $envelope,
        Middleware $next = null,
    ): Envelope {
        $stamped = $this->locator->locate($envelope)
            ->handle($envelope)
            ->withStamp(new HandlerStamp());

        return $next
            ? $next->handle($stamped)
            : $stamped;
    }
}
