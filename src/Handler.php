<?php

declare(strict_types=1);

namespace MiniBus;

interface Handler
{
    public function handle(Envelope $envelope): Envelope;
}
