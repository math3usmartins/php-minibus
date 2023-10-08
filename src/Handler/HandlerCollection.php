<?php

namespace MiniBus\Handler;

use MiniBus\Envelope;
use MiniBus\Handler;

final class HandlerCollection
{
    /**
     * @var Handler[]
     */
    private $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @return Envelope
     */
    public function handle(Envelope $envelope)
    {
        return array_reduce(
            $this->handlers,
            function (Envelope $envelope, Handler $handler) {
                return $handler->handle($envelope);
            },
            $envelope
        );
    }
}
