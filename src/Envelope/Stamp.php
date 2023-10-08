<?php

namespace MiniBus\Envelope;

interface Stamp
{
    /**
     * @return string
     */
    public function name();

    /**
     * @return bool
     */
    public function isEqualTo(self $anotherStamp);
}
