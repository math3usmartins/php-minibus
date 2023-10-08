<?php

namespace MiniBus\Envelope;

use Closure;
use MiniBus\Envelope;

final class EnvelopeCollection
{
    /**
     * @var Envelope[]
     */
    private $items;

    /**
     * @param Envelope[] $items
     */
    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    /**
     * @return Envelope[]
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @return self
     */
    public function with(Envelope $envelope)
    {
        return new self(array_merge($this->items, [$envelope]));
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * @return array
     */
    public function map(Closure $closure)
    {
        return array_map($closure, $this->items);
    }

    /**
     * @return self
     */
    public function filter(Closure $closure)
    {
        $matchingItems = array_values(
            array_filter($this->items, $closure)
        );

        return new self($matchingItems);
    }
}
