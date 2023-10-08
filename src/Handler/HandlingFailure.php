<?php

namespace MiniBus\Handler;

use MiniBus\Envelope;

/**
 * @template T
 */
final class HandlingFailure
{
    /**
     * @var Envelope
     */
    private $envelope;

    /**
     * @var T
     */
    private $reason;

    /**
     * @param T $reason
     */
    public function __construct(
        Envelope $envelope,
        $reason
    ) {
        $this->envelope = $envelope;
        $this->reason = $reason;
    }

    /**
     * @return Envelope
     */
    public function envelope()
    {
        return $this->envelope;
    }

    /**
     * @return T
     */
    public function reason()
    {
        return $this->reason;
    }
}
