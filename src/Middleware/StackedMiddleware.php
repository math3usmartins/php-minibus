<?php

namespace MiniBus\Middleware;

use MiniBus\Envelope;
use MiniBus\Middleware;

final class StackedMiddleware implements Middleware
{
    /**
     * @var Middleware
     */
    private $current;

    /**
     * @var Middleware|null
     */
    private $next;

    public function __construct(Middleware $current, Middleware $next = null)
    {
        $this->current = $current;
        $this->next = $next;
    }

    public function handle(Envelope $envelope, Middleware $next = null)
    {
        return $this->current->handle($envelope, $this->next ?: $next);
    }
}
