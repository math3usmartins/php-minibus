<?php

declare(strict_types=1);

namespace MiniBus\Dispatcher;

use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Message;
use MiniBus\MessageDispatcher;
use MiniBus\Middleware\MiddlewareStack;

final class DefaultDispatcher implements MessageDispatcher
{
    public function __construct(
        private EnvelopeFactory $envelopeFactory,
        private MiddlewareStack $stack,
    ) {}

    public function dispatch(
        Message $message,
        StampCollection $stamps,
    ): Envelope {
        return $this->stack->handle(
            $this->envelopeFactory->create($message, $stamps),
        );
    }
}
