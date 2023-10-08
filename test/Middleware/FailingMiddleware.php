<?php

namespace MiniBus\Test\Middleware;

use Exception;
use Functional\Either;
use MiniBus\Envelope;
use MiniBus\Handler\HandlingFailure;
use MiniBus\Middleware;

final class FailingMiddleware implements Middleware
{
    /**
     * @var Exception
     */
    private $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope, Middleware $next = null)
    {
        throw $this->exception;
    }
}
