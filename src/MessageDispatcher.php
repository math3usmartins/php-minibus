<?php

namespace MiniBus;

use MiniBus\Envelope\Stamp\StampCollection;

interface MessageDispatcher
{
    /**
     * @return Envelope
     */
    public function dispatch(Message $message, StampCollection $stamps);
}
