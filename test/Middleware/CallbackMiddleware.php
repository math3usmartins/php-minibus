<?php

namespace MiniBus\Test\Middleware;

use Closure;
use Functional\Either;
use MiniBus\Envelope;
use MiniBus\Handler\HandlingFailure;
use MiniBus\Middleware;
use function call_user_func;

final class CallbackMiddleware implements Middleware
{
    /**
     * @var Closure
     */
    private $callback;

    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope, Middleware $next = null)
    {
        return call_user_func($this->callback, $envelope, $next);
    }
}
