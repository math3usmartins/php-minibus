<?php

declare(strict_types=1);

namespace MiniBus\Test\Envelope\Stamp;

use MiniBus\Envelope\Stamp;

final class StubStamp implements Stamp
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $keyValue;

    public function __construct(string $name, string $keyValue)
    {
        $this->name = $name;
        $this->keyValue = $keyValue;
    }

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
