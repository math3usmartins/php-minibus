<?php

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

    /**
     * @return Envelope
     */
    public function withStamp(Stamp $stamp)
    {
        return new self(
            $this->message,
            $this->stamps->with($stamp)
        );
    }

    /**
     * @return StampCollection
     */
    public function stamps()
    {
        return $this->stamps;
    }

    /**
     * @return Message
     */
    public function message()
    {
        return $this->message;
    }
}
