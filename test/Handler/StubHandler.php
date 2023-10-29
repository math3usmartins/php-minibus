<?php

declare(strict_types=1);

namespace MiniBus\Test\Handler;

use Exception;
use MiniBus\Envelope;
use MiniBus\Handler;

final class StubHandler implements Handler
{
    public function __construct(private ?Envelope $result = null) {}

    /**
     * @throws Exception
     */
    public function handle(Envelope $envelope): Envelope
    {
        if (!$this->result) {
            throw new Exception('This handler should not have been called');
        }

        return $this->result;
    }
}
