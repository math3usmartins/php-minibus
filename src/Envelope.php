<?php

namespace MiniBus;

use MiniBus\Envelope\Stamp;
use MiniBus\Envelope\Stamp\StampCollection;

interface Envelope
{
    /**
     * @return Envelope
     */
    public function withStamp(Stamp $stamp);

    /**
     * @return StampCollection
     */
    public function stamps();

    /**
     * @return Message
     */
    public function message();
}
