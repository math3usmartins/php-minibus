<?php

declare(strict_types=1);

namespace MiniBus\Handler\Locator;

use MiniBus\Envelope;
use MiniBus\Handler\HandlerCollection;
use MiniBus\Handler\HandlerLocator;

final class SubjectLocator implements HandlerLocator
{
    /**
     * @param HandlerCollection[] $handlersPerSubject
     */
    public function __construct(private array $handlersPerSubject) {}

    public function locate(Envelope $envelope): HandlerCollection
    {
        $subject = $envelope->message()->subject();

        return $this->handlersPerSubject[$subject]
            ?? new HandlerCollection([]);
    }
}
