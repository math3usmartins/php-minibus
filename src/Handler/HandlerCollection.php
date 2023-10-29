<?php

declare(strict_types=1);

namespace MiniBus\Handler;

use MiniBus\Envelope;
use MiniBus\Handler;

final class HandlerCollection
{
    public function __construct(
        /**
         * @var Handler[]
         */
        private array $handlers,
    ) {}

    public function handle(Envelope $envelope): Envelope
    {
        return array_reduce(
            $this->handlers,
            static fn (Envelope $envelope, Handler $handler) => $handler->handle($envelope),
            $envelope,
        );
    }
}
