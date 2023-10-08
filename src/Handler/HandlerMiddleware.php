<?php

namespace MiniBus\Handler;

use MiniBus\Envelope;
use MiniBus\Middleware;

final class HandlerMiddleware implements Middleware
{
    /**
     * @var HandlerLocator
     */
    private $locator;

    public function __construct(
        HandlerLocator $locator
    ) {
        $this->locator = $locator;
    }

    public function handle(Envelope $envelope, Middleware $next = null)
    {
        $stamped = $this->locator->locate($envelope)
            ->handle($envelope)
            ->withStamp(new HandlerStamp());

        return $next
            ? $next->handle($stamped)
            : $stamped;
    }
}
