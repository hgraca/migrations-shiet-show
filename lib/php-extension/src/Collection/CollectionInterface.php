<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Collection;

use Countable;
use Iterator;
use IteratorAggregate;

/**
 * @template TKey as array-key
 * @template TValue
 */
interface CollectionInterface extends Countable, IteratorAggregate
{
    /**
     * @return Iterator<TKey, TValue>
     */
    public function getIterator(): Iterator;

    public function count(): int;

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array;

    /**
     * @param TValue $item
     */
    public function contains($item): bool;

    /**
     * @return TValue
     */
    public function first();

    /**
     * @return TValue
     */
    public function last();

    /**
     * @return CollectionInterface<TKey, TValue>
     */
    public function reverse(): CollectionInterface;

    /**
     * @return TValue
     */
    public function firstWhere(callable $filter);

    /**
     * @return Collection<Tkey, TValue>
     */
    public function values();

    /**
     * @param ?Closure(TValue): bool $filter
     *
     * @return Collection<TKey, TValue>
     */
    public function filter(callable $filter = null);

    public function isEmpty(): bool;
}
