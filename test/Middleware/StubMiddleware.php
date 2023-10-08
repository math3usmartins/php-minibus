<?php

namespace MiniBus\Test\Middleware;

use Functional\Either;
use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Handler\HandlingFailure;
use MiniBus\Middleware;

final class StubMiddleware implements Middleware
{
    private $handled = false;

    private $callNext;

    public function __construct($callNext = true)
    {
        $this->callNext = $callNext;
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope, Middleware $next = null)
    {
        $this->handled = true;

        if (!$this->callNext) {
            return Right::fromValue($envelope);
        }

        return $next
            ? $next->handle($envelope)
            : Right::fromValue($envelope);
    }

    public function handled()
    {
        return $this->handled;
    }
}
