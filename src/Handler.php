<?php

namespace MiniBus;

interface Handler
{
    /**
     * @return Envelope
     */
    public function handle(Envelope $envelope);
}
