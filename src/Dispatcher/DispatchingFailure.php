<?php

namespace MiniBus\Dispatcher;

use MiniBus\Message;

/**
 * @template T
 */
final class DispatchingFailure
{
    /**
     * @var Message
     */
    private $message;

    /**
     * @var T
     */
    private $reason;

    /**
     * @param T $reason
     */
    public function __construct(
        Message $message,
        $reason
    ) {
        $this->message = $message;
        $this->reason = $reason;
    }

    /**
     * @return Message
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @return T
     */
    public function reason()
    {
        return $this->reason;
    }
}
