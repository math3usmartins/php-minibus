<?php

namespace MiniBus\Test\Handler;

use MiniBus\Envelope;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Handler\HandlerLocator;

final class StubHandlerLocator implements HandlerLocator
{
    /**
     * @var HandlerCollection
     */
    private $handlerCollection;

    public function __construct(HandlerCollection $handlerCollection)
    {
        $this->handlerCollection = $handlerCollection;
    }

    public function locate(Envelope $envelope)
    {
        return $this->handlerCollection;
    }
}
