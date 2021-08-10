<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Exception\OutOfBoundsException;
use Acme\PhpExtension\Helper\ArrayHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class ArrayHelperTest extends AbstractTestCase
{
    /**
     * @test
     * @dataProvider provideArrayKeyValues
     */
    public function it_detects_if_array_is_not_key_value_string_map(array $array, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, ArrayHelper::isKeyValueStringMap($array));
    }

    public function provideArrayKeyValues()
    {
        return [
            [['a' => 'a', 'b' => 'b', 'c' => 'c'], true],
            [['a' => 1, 'b' => 'b', 'c' => 'c'], false],
            [['a' => 'a', 2 => 'b', 'c' => 'c'], false],
            [['a' => 1, 2 => 'b', 'c' => 'c'], false],
        ];
    }

    /**
     * @test
     * @dataProvider provideArrayKeys
     */
    public function it_detects_if_array_has_non_string_keys(array $array, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, ArrayHelper::hasOnlyStringKeys($array));
    }

    public function provideArrayKeys()
    {
        return [
            [['a' => 1, 'b' => 'b', 'c' => 'c'], true],
            [['a' => 'a', 2 => 'b', 'c' => 'c'], false],
        ];
    }

    /**
     * @test
     * @dataProvider provideArrayValues
     */
    public function it_detects_if_array_has_non_string_values(array $array, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, ArrayHelper::hasOnlyStringValues($array));
    }

    public function provideArrayValues()
    {
        return [
            [['a', 'b', 'c'], true],
            [['a', 1, 'c'], false],
        ];
    }

    /**
     * @test
     */
    public function filter_by_keys(): void
    {
        self::assertSame(['a' => 1], ArrayHelper::filterByKeys(['a' => 1, 'b' => 2], ['a', 'c']));
    }

    /**
     * @test
     *
     * @dataProvider provideEnsureAllStringsAreWrappedInCases
     */
    public function ensure_all_strings_are_wrapped_in(string $left, string $right, array $haystack, array $expected): void
    {
        self::assertSame($expected, ArrayHelper::ensureAllStringsAreWrappedIn($left, $right, $haystack));
    }

    public function provideEnsureAllStringsAreWrappedInCases(): array
    {
        return [
            ['<p>', '</p>', ['aaabbb', 'ccc'], ['<p>aaabbb</p>', '<p>ccc</p>']],
            ['<p>', '</p>', ['<p>aaabbb</p>', '<p>ccc'], ['<p>aaabbb</p>', '<p>ccc</p>']],
        ];
    }

    /**
     * @test
     */
    public function ensure_all_strings_are_wrapped_in_evaluates_given_callable(): void
    {
        $array = ['aaabbb', 'ccc'];

        self::assertSame(
            $array,
            ArrayHelper::ensureAllStringsAreWrappedIn(
                '<p>',
                '</p>',
                $array,
                function ($value) {
                    return true;
                }
            )
        );
    }

    /**
     * @test
     *
     * @dataProvider provideEnsureAllStringsAreWrappedInCases
     */
    public function array_map_recursive(): void
    {
        $haystack = [
            'x',
            [
                'y',
                'z',
            ],
            'w',
        ];
        $expected = [
            'a',
            [
                'a',
                'a',
            ],
            'a',
        ];

        self::assertSame($expected, ArrayHelper::arrayMapRecursive($haystack, function () {return 'a'; }));
        self::assertSame(
            [
                'x',
                [
                    'y',
                    'z',
                ],
                'w',
            ],
            $haystack
        );
    }

    /**
     * @test
     * @dataProvider provideArraysForSettingThatCreatesPath
     */
    public function it_sets_value_in_path_and_creates_path_if_it_does_not_exist(
        array $array,
        string $path,
        $value,
        array $expectedResult
    ): void {
        self::assertEquals($expectedResult, ArrayHelper::set($array, $path, $value, true));
    }

    public function provideArraysForSettingThatCreatesPath()
    {
        return [
            [['a' => 'a'], 'b', 'b', ['a' => 'a', 'b' => 'b']],
            [['a' => 'a'], 'b' . DIRECTORY_SEPARATOR . 'c' . DIRECTORY_SEPARATOR . 'd', 'b', ['a' => 'a', 'b' => ['c' => ['d' => 'b']]]],
        ];
    }

    /**
     * @test
     */
    public function it_throws_exception_if_setting_in_path_that_does_not_exist(): void
    {
        $array = ['a' => 'a'];
        $path = 'b' . DIRECTORY_SEPARATOR . 'c' . DIRECTORY_SEPARATOR . 'd';
        $value = 'b';

        self::expectException(OutOfBoundsException::class);
        ArrayHelper::set($array, $path, $value);
    }

    /**
     * @test
     */
    public function it_can_get_from_path(): void
    {
        $array = ['a' => 'a', 'b' => ['c' => ['d' => 'b']]];
        $path = 'b' . DIRECTORY_SEPARATOR . 'c' . DIRECTORY_SEPARATOR . 'd';
        $expectedValue = 'b';

        self::assertEquals($expectedValue, ArrayHelper::get($array, $path));
    }

    /**
     * @test
     */
    public function it_throws_exception_if_getting_from_path_that_does_not_exist(): void
    {
        $array = ['a' => 'a'];
        $path = 'b' . DIRECTORY_SEPARATOR . 'c' . DIRECTORY_SEPARATOR . 'd';
        $expectedValue = 'b';

        self::expectException(OutOfBoundsException::class);
        ArrayHelper::get($array, $path);
    }

    /**
     * @test
     */
    public function it_can_unset_path(): void
    {
        $array = ['a' => 'a', 'b' => ['c' => ['d' => 'b']]];
        $path = 'b' . DIRECTORY_SEPARATOR . 'c';
        $expectedValue = ['a' => 'a', 'b' => []];

        self::assertEquals($expectedValue, ArrayHelper::unset($array, $path));
    }

    /**
     * @test
     */
    public function it_throws_exception_if_unsetting_path_that_does_not_exist(): void
    {
        $array = ['a' => 'a'];
        $path = 'b' . DIRECTORY_SEPARATOR . 'c';

        self::expectException(OutOfBoundsException::class);
        ArrayHelper::unset($array, $path);
    }

    /**
     * @test
     */
    public function it_knows_if_path_exists(): void
    {
        $array = ['a' => 'a', 'b' => ['c' => ['d' => 'b']]];
        $existingPath1 = 'b' . DIRECTORY_SEPARATOR . 'c';
        $existingPath2 = 'b' . DIRECTORY_SEPARATOR . 'c' . DIRECTORY_SEPARATOR . 'd';
        $nonExistingPath = 'b' . DIRECTORY_SEPARATOR . 'z';

        self::assertTrue(ArrayHelper::has($array, $existingPath1));
        self::assertTrue(ArrayHelper::has($array, $existingPath2));
        self::assertFalse(ArrayHelper::has($array, $nonExistingPath));
    }
}
