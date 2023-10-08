<?php

declare(strict_types=1);

namespace MiniBus\Handler;

use MiniBus\Envelope;

interface HandlerLocator
{
    public function locate(Envelope $envelope): HandlerCollection;
}
