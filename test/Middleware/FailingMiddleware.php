<?php

namespace MiniBus\Test\Middleware;

use Exception;
use MiniBus\Envelope;
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

    public function handle(Envelope $envelope, Middleware $next = null)
    {
        throw $this->exception;
    }
}
