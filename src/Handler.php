<?php

namespace MiniBus;

use Functional\Either;
use MiniBus\Handler\HandlingFailure;

interface Handler
{
    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope);

    /**
     * @return bool
     */
    public function supports(Envelope $envelope);
}
