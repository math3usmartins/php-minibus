<?php

declare(strict_types=1);

namespace MiniBus\Test\Handler;

use MiniBus\Envelope;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Handler\HandlerLocator;

final class StubHandlerLocator implements HandlerLocator
{
    public function __construct(private HandlerCollection $handlerCollection) {}

    public function locate(Envelope $envelope): HandlerCollection
    {
        return $this->handlerCollection;
    }
}
