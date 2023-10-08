<?php

namespace MiniBus\Envelope;

use MiniBus\Envelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;

interface EnvelopeFactory
{
    /**
     * @return Envelope
     */
    public function create(Message $message, StampCollection $stamps);
}
