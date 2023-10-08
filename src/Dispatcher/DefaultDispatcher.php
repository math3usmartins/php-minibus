<?php

namespace MiniBus\Dispatcher;

use MiniBus\Envelope\EnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;
use MiniBus\MessageDispatcher;
use MiniBus\Middleware\MiddlewareStack;

final class DefaultDispatcher implements MessageDispatcher
{
    /**
     * @var EnvelopeFactory
     */
    private $envelopeFactory;

    /**
     * @var MiddlewareStack
     */
    private $stack;

    public function __construct(
        EnvelopeFactory $envelopeFactory,
        MiddlewareStack $stack
    ) {
        $this->envelopeFactory = $envelopeFactory;
        $this->stack = $stack;
    }

    public function dispatch(Message $message, StampCollection $stamps)
    {
        return $this->stack->handle(
            $this->envelopeFactory->create($message, $stamps)
        );
    }
}
