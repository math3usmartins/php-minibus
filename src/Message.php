<?php

declare(strict_types=1);

namespace MiniBus;

interface Message
{
    public function subject(): string;

    public function normalize(): array;
}
