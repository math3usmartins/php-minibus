<?php

declare(strict_types=1);

namespace MiniBus\Handler;

use MiniBus\Envelope\Stamp;

final class HandlerStamp implements Stamp
{
    const NAME = 'handler';

    public function name(): string
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp): bool
    {
        return $anotherStamp instanceof self;
    }
}
