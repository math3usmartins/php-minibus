<?php

declare(strict_types=1);

namespace MiniBus\Envelope\Stamp;

use MiniBus\Envelope\Stamp;

final class StampCollection
{
    /**
     * @var Stamp[][]
     */
    private array $items;

    /**
     * @param Stamp[] $items
     */
    public function __construct(array $items)
    {
        $this->items = self::groupByName($items);
    }

    public function with(Stamp $stamp): self
    {
        return new self(array_merge($this->flat(), [$stamp]));
    }

    public function contains(Stamp $stamp): bool
    {
        $name = $stamp->name();

        if (empty($this->items[$name])) {
            return false;
        }

        foreach ($this->items[$name] as $item) {
            if ($item->isEqualTo($stamp)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     */
    public function all($name): self
    {
        $items = !empty($this->items[$name])
            ? $this->items[$name]
            : [];

        return new self($items);
    }

    /**
     * @param string $name
     *
     * @return Stamp|null
     */
    public function last($name)
    {
        $items = !empty($this->items[$name])
            ? array_reverse($this->items[$name])
            : [];

        return $items[0]
            ?? null;
    }

    /**
     * @param Stamp[] $items
     *
     * @return Stamp[][]
     */
    private static function groupByName(array $items): array
    {
        return array_reduce(
            $items,
            static function (array $carry, Stamp $stamp) {
                $name = $stamp->name();
                $carry[$name][] = $stamp;

                return $carry;
            },
            [],
        );
    }

    /**
     * @return Stamp[]
     */
    private function flat(): array
    {
        return array_reduce(
            $this->items,
            static fn (array $flat, array $subItems) => array_merge($flat, $subItems),
            [],
        );
    }
}
