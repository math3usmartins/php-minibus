<?php

namespace MiniBus;

interface Middleware
{
    /**
     * @return Envelope
     */
    public function handle(Envelope $envelope, self $next = null);
}
