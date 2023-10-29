<?php

declare(strict_types=1);

namespace MiniBus;

interface Middleware
{
    public function handle(
        Envelope $envelope,
        self $next = null,
    ): Envelope;
}
