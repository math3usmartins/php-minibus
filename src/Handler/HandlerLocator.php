<?php

namespace MiniBus\Handler;

use MiniBus\Envelope;

interface HandlerLocator
{
    /**
     * @return HandlerCollection
     */
    public function locate(Envelope $envelope);
}
