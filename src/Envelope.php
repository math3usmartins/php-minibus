<?php

declare(strict_types=1);

namespace MiniBus;

use MiniBus\Envelope\Stamp;
use MiniBus\Envelope\Stamp\StampCollection;

interface Envelope
{
    public function withStamp(Stamp $stamp): self;

    public function stamps(): StampCollection;

    public function message(): Message;
}
