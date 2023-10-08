<?php

namespace MiniBus\Envelope\Stamp;

use MiniBus\Envelope\Stamp;
use function count;

final class StampCollection
{
    /**
     * @var Stamp[][]
     */
    private $items;

    public function __construct(Stamp ...$items)
    {
        $this->items = self::groupByName($items);
    }

    public function count()
    {
        return count($this->flat());
    }

    /**
     * @return self
     */
    public function with(Stamp $stamp)
    {
        return new self(...array_merge($this->flat(), [$stamp]));
    }

    /**
     * @return bool
     */
    public function contains(Stamp $stamp)
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
     *
     * @return self
     */
    public function all($name)
    {
        $items = !empty($this->items[$name])
            ? $this->items[$name]
            : [];

        return new self(...$items);
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

        return isset($items[0])
            ? $items[0]
            : null;
    }

    /**
     * @param Stamp[] $items
     *
     * @return Stamp[][]
     */
    private static function groupByName(array $items)
    {
        return array_reduce(
            $items,
            function (array $carry, Stamp $stamp) {
                $name = $stamp->name();
                $carry[$name][] = $stamp;

                return $carry;
            },
            []
        );
    }

    /**
     * @return Stamp[]
     */
    private function flat()
    {
        return array_reduce(
            $this->items,
            function (array $flat, array $subItems) {
                return array_merge($flat, $subItems);
            },
            []
        );
    }
}
