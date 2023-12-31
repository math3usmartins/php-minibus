<?php

declare(strict_types=1);

namespace MiniBus\Test\Middleware;

use Closure;
use MiniBus\Envelope;
use MiniBus\Middleware;

use function call_user_func;

final class CallbackMiddleware implements Middleware
{
    public function __construct(private Closure $callback) {}

    public function handle(Envelope $envelope, Middleware $next = null): Envelope
    {
        return call_user_func($this->callback, $envelope, $next);
    }
}
