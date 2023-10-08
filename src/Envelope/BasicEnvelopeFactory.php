<?php

namespace MiniBus\Envelope;

use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;

final class BasicEnvelopeFactory implements EnvelopeFactory
{
    public function create(Message $message, StampCollection $stamps)
    {
        return new BasicEnvelope($message, $stamps);
    }
}
