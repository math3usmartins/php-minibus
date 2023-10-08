<?php

namespace MiniBus\Dispatcher;

use Exception;
use Functional\Either;
use Functional\Either\Left;
use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeFactory;
use MiniBus\Envelope\Stamp\StampCollection;
use MiniBus\Handler\HandlingFailure;
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

    /**
     * @return Either<HandlingFailure|Envelope>|Either<DispatchingFailure|Envelope>
     */
    public function dispatch(Message $message, StampCollection $stamps)
    {
        try {
            $envelope = $this->envelopeFactory->create($message, $stamps);
        } catch (Exception $exception) {
            return Left::fromValue(new DispatchingFailure($message, $exception));
        }

        try {
            return $this->stack->handle($envelope);
        } catch (Exception $e) {
            return Left::fromValue(new HandlingFailure($envelope, $e));
        }
    }
}
