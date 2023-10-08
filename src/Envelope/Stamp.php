<?php

declare(strict_types=1);

namespace MiniBus\Envelope;

interface Stamp
{
    public function name(): string;

    public function isEqualTo(self $anotherStamp): bool;
}
