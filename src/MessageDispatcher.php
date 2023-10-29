<?php

declare(strict_types=1);

namespace MiniBus;

use MiniBus\Envelope\Stamp\StampCollection;

interface MessageDispatcher
{
    public function dispatch(
        Message $message,
        StampCollection $stamps,
    ): Envelope;
}
