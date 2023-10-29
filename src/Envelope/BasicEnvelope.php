<?php

declare(strict_types=1);

namespace MiniBus\Envelope;

use MiniBus\Envelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;

final class BasicEnvelope implements Envelope
{
    public function __construct(
        private Message $message,
        private StampCollection $stamps,
    ) {}

    public function withStamp(Stamp $stamp): Envelope
    {
        return new self(
            $this->message,
            $this->stamps->with($stamp),
        );
    }

    public function stamps(): StampCollection
    {
        return $this->stamps;
    }

    public function message(): Message
    {
        return $this->message;
    }
}
