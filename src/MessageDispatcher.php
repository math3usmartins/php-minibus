<?php

namespace MiniBus;

use Functional\Either;
use MiniBus\Dispatcher\DispatchingFailure;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler\HandlingFailure;

interface MessageDispatcher
{
    /**
     * @return Either<HandlingFailure|Envelope>|Either<DispatchingFailure|Envelope>
     */
    public function dispatch(Message $message, StampCollection $stamps);
}
