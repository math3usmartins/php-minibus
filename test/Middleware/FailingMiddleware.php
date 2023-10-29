<?php

declare(strict_types=1);

namespace MiniBus\Test\Middleware;

use Exception;
use MiniBus\Envelope;
use MiniBus\Middleware;

final class FailingMiddleware implements Middleware
{
    public function __construct(private Exception $exception) {}

    public function handle(Envelope $envelope, Middleware $next = null): Envelope
    {
        throw $this->exception;
    }
}
