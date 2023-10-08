<?php

namespace MiniBus;

use Functional\Either;
use MiniBus\Handler\HandlingFailure;

interface Middleware
{
    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope, self $next = null);
}
