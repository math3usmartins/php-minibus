<?php

declare(strict_types=1);

namespace MiniBus\Test\Envelope\Stamp;

use MiniBus\Envelope\Stamp;

final class StubStamp implements Stamp
{
    public function __construct(private string $name, private string $keyValue) {}

    public function name(): string
    {
        return $this->name;
    }

    public function keyValue(): string
    {
        return $this->keyValue;
    }

    public function isEqualTo(Stamp $anotherStamp): bool
    {
        return ($anotherStamp instanceof self)
            && ($anotherStamp->name === $this->name)
            && ($anotherStamp->keyValue === $this->keyValue);
    }
}
