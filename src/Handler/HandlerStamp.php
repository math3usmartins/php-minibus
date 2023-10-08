<?php

namespace MiniBus\Handler;

use MiniBus\Envelope\Stamp;

final class HandlerStamp implements Stamp
{
    const NAME = 'handler';

    public function name()
    {
        return self::NAME;
    }

    public function isEqualTo(Stamp $anotherStamp)
    {
        return $anotherStamp instanceof self;
    }
}
