<?php

declare(strict_types=1);

namespace MiniBus\Envelope;

use MiniBus\Envelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;

final class BasicEnvelopeFactory implements EnvelopeFactory
{
    public function create(Message $message, StampCollection $stamps): Envelope
    {
        return new BasicEnvelope($message, $stamps);
    }
}
