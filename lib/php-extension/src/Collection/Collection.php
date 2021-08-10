<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Collection;

use ArrayIterator;
use Iterator;
use function count;

/**
 * @template TKey as array-key
 * @template TValue
 * @implements CollectionInterface<TKey, TValue>
 */
class Collection implements CollectionInterface
{
    /** @var array<TKey, TValue> */
    protected $itemList;

    /**
     * @param array<TKey, TValue> $itemList
     */
    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    /**
     * @return Iterator<TKey, TValue>
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->itemList);
    }

    public function count(): int
    {
        return count($this->itemList);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        return $this->itemList;
    }

    /**
     * @param TValue $item
     */
    public function contains($item): bool
    {
        return in_array($item, $this->itemList, true);
    }

    public function containsWhere(callable $filter): bool
    {
        try {
            $this->firstWhere($filter);

            return true;
        } catch (NoResultFoundException $error) {
            return false;
        }
    }

    /**
     * @return TValue
     */
    public function first()
    {
        foreach ($this->itemList as $value) {
            return $value;
        }

        throw new EmptyCollectionException();
    }

    /**
     * @return TValue
     */
    public function last()
    {
        return $this->reverse()
                    ->first();
    }

    /**
     * @return CollectionInterface<TKey, TValue>
     */
    public function reverse(): CollectionInterface
    {
        return new Collection(array_reverse($this->itemList));
    }

    /**
     * @return TValue
     */
    public function firstWhere(callable $filter)
    {
        foreach ($this->itemList as $value) {
            if ($filter($value) === true) {
                return $value;
            }
        }

        throw new NoResultFoundException();
    }

    /**
     * @return Collection<Tkey, TValue>
     */
    public function values()
    {
        return new static(array_values($this->itemList));
    }

    /**
     * @param ?Closure(TValue): bool $filter
     *
     * @return Collection<TKey, TValue>
     */
    public function filter(callable $filter = null)
    {
        if ($filter === null) {
            return new static(array_filter($this->itemList));
        }

        return new static(array_filter(
            $this->itemList,
            $filter
        ));
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }
}
