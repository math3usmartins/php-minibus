<?php

namespace MiniBus\Test\Handler;

use Exception;
use Functional\Either;
use Functional\Either\Left;
use Functional\Either\Right;
use MiniBus\Envelope;
use MiniBus\Handler;
use MiniBus\Handler\HandlingFailure;

final class StubHandler implements Handler
{
    /**
     * @var bool
     */
    private $supports;

    /**
     * @var Envelope|null
     */
    private $result;

    /**
     * @param bool $supports
     */
    public function __construct(
        $supports,
        Envelope $result = null
    ) {
        $this->supports = $supports;
        $this->result = $result;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Envelope $envelope)
    {
        return $this->supports;
    }

    /**
     * @return Either<HandlingFailure|Envelope>
     */
    public function handle(Envelope $envelope)
    {
        return $this->result
            ? Right::fromValue($this->result)
            : Left::fromValue(new HandlingFailure($envelope, new Exception('This handler should not have been called')));
    }
}
