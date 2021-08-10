<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test;

use Acme\PhpExtension\Collection\Collection;
use Acme\PhpExtension\Collection\NoResultFoundException;
use stdClass;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class CollectionTest extends AbstractTestCase
{
    /**
     * @test
     *
     * @param mixed $targetItem
     * @dataProvider containsDataProvider
     */
    public function contains_item(Collection $collection, $targetItem, bool $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            $collection->contains($targetItem)
        );
    }

    public function containsDataProvider(): array
    {
        $object = new StdClass();
        $collection = new Collection([$object]);

        return [
          [
              new Collection([1, 2, 3]),
              3,
              true,
          ],
          [
              new Collection(['foo', 'bar']),
              'fooBar',
              false,
          ],
          [
              $collection,
              $object,
              true,
          ],
        ];
    }

    /**
     * @test
     */
    public function returns_first_item(): void
    {
        $firstValue = 'a';

        $collection = new Collection([$firstValue, 'b', 'c']);
        $firstItem = $collection->first();

        self::assertSame($firstValue, $firstItem);
    }

    /**
     * @test
     */
    public function returns_last_item(): void
    {
        $lastValue = 'c';

        $collection = new Collection(['a', 'b', $lastValue]);
        $lastItem = $collection->last();

        self::assertSame($lastValue, $lastItem);
    }

    /**
     * @test
     */
    public function returns_reversed_list(): void
    {
        $original = [1, 2, 3];
        $reversed = [3, 2, 1];

        $collection = new Collection($original);
        $reversedCollection = $collection->reverse();

        self::assertSame($reversedCollection->toArray(), $reversed);
    }

    /**
     * @test
     */
    public function returns_first_item_based_on_callable(): void
    {
        $firstValue = ['test' => true];
        $collection = new Collection([
            $firstValue,
            ['test' => false],
            ['test' => false],
        ]);

        self::assertSame(
            $firstValue,
            $collection->firstWhere(function ($value) {
                return $value['test'] === true;
            })
        );
    }

    /**
     * @test
     * @dataProvider containsCallableDataProvider
     */
    public function contains_item_based_on_a_callable(Collection $collection, callable $whereFunction, bool $expectedResult): void
    {
        self::assertSame(
            $expectedResult,
            $collection->containsWhere($whereFunction)
        );
    }

    public function containsCallableDataProvider(): array
    {
        $firstObject = new StdClass();
        $firstObject->color = 'green';
        $secondObject = new StdClass();
        $secondObject->collor = 'amber';
        $thirdObject = new StdClass();
        $thirdObject->collor = 'red';

        $collection = new Collection([
            $firstObject,
            $secondObject,
            $thirdObject,
        ]);

        return [
            [
                new collection([1, 2, 3]),
                function ($value) {
                    return $value === 2;
                },
                true,
            ],
            [
                $collection,
                function ($value) {
                    return $value->color === 'green';
                },
                true,
            ],
            [
                new collection(['foo', 'bar']),
                function ($value) {
                    return $value === 2;
                },
                false,
            ],
        ];
    }

    /**
     * @test
     */
    public function returns_values_with_reset_keys(): void
    {
        $collection = new Collection(['a' => 1, 'b' => 2, 'c' => 3]);

        self::assertSame(
            [1, 2, 3],
            $collection->values()->toArray()
        );
    }

    /**
     * @test
     */
    public function throws_exception_on_first_where_if_nothing_found(): void
    {
        $collection = new Collection([1, 2, 3]);

        $this->expectException(NoResultFoundException::class);
        $collection->firstWhere(function ($value) {
            return false;
        });
    }

    /**
     * @test
     * @dataProvider filterProvider
     */
    public function returns_items_based_on_filter(array $dataset, callable $filter = null, array $expectedResult): void
    {
        $collection = new Collection($dataset);

        $resultSet = $collection->filter($filter)
                                ->values()
                                ->toArray();

        self::assertSame($resultSet, $expectedResult);
    }

    public function filterProvider(): array
    {
        return [
            [
                [0, 1, null, '', 2, false],
                null,
                [1, 2],
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9],
                function (int $value) {
                    return $value > 5;
                },
                [6, 7, 8, 9],
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9],
                function (int $value) {
                    return $value < 5;
                },
                [1, 2, 3, 4],
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9],
                function (int $value) {
                    return $value === 5;
                },
                [5],
            ],
        ];
    }

    /**
     * @test
     */
    public function can_check_if_empty(): void
    {
        $emptyCollection = new Collection();
        $nonEmptyCollection = new Collection([1, 2, 3]);

        self::assertTrue($emptyCollection->isEmpty());
        self::assertFalse($nonEmptyCollection->isEmpty());
    }
}
