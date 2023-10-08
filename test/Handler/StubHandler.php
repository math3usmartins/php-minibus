<?php

namespace MiniBus\Test\Handler;

use Exception;
use MiniBus\Envelope;
use MiniBus\Handler;

final class StubHandler implements Handler
{
    /**
     * @var Envelope|null
     */
    private $result;

    public function __construct(Envelope $result = null)
    {
        $this->result = $result;
    }

    /**
     * @throws Exception
     *
     * @return Envelope
     */
    public function handle(Envelope $envelope)
    {
        if (!$this->result) {
            throw new Exception('This handler should not have been called');
        }

        return $this->result;
    }
}
