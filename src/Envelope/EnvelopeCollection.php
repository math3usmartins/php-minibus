<?php

declare(strict_types=1);

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
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @return self
     */
    public function with(Envelope $envelope): self
    {
        return new self(array_merge($this->items, [$envelope]));
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * @return self
     */
    public function map(Closure $closure)
    {
        $mappedItems = array_map($closure, $this->items);

        return new self($mappedItems);
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
