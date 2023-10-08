<?php

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

    /**
     * @param string $name
     * @param string $keyValue
     */
    public function __construct($name, $keyValue)
    {
        $this->name = $name;
        $this->keyValue = $keyValue;
    }

    public function name()
    {
        return $this->name;
    }

    public function keyValue()
    {
        return $this->keyValue;
    }

    public function isEqualTo(Stamp $anotherStamp)
    {
        return ($anotherStamp instanceof self)
            && ($anotherStamp->name === $this->name)
            && ($anotherStamp->keyValue === $this->keyValue);
    }
}
