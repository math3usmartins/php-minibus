<?php

declare(strict_types=1);

namespace MiniBus\Envelope;

use MiniBus\Envelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;

interface EnvelopeFactory
{
    public function create(
        Message $message,
        StampCollection $stamps,
    ): Envelope;
}
