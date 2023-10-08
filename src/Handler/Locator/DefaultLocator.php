<?php

namespace MiniBus\Handler\Locator;

use MiniBus\Envelope;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Handler\HandlerLocator;

final class DefaultLocator implements HandlerLocator
{
    /**
     * @var HandlerCollection
     */
    private $handlers;

    public function __construct(HandlerCollection $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @return HandlerCollection
     */
    public function locate(Envelope $envelope)
    {
        return $this->handlers->findForEnvelope($envelope);
    }
}
