<?php

declare(strict_types=1);

namespace MiniBus\Envelope;

use MiniBus\Envelope;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;

final class BasicEnvelope implements Envelope
{
    /**
     * @var Message
     */
    private $message;

    /**
     * @var StampCollection
     */
    private $stamps;

    public function __construct(Message $message, StampCollection $stamps)
    {
        $this->message = $message;
        $this->stamps = $stamps;
    }

    public function withStamp(Stamp $stamp): Envelope
    {
        return new self(
            $this->message,
            $this->stamps->with($stamp)
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
