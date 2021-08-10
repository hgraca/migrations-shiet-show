<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test;

use Acme\PhpExtension\ReferenceHelper;
use stdClass;

/**
 * @internal
 *
 * @small
 */
final class ReferenceHelperTest extends AbstractTestCase
{
    /**
     * @test
     * @dataProvider provideObjectsAndArrays
     */
    public function objects_and_array_references_are_compared_correctly(&$a, &$b, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, ReferenceHelper::isSame($a, $b));
    }

    public function provideObjectsAndArrays(): array
    {
        $x = new stdClass();
        $y = $x;
        $z = new stdClass();
        $a = ['foo'];
        $b = ['foo'];
        $c = &$a;
        $d = $a;

        return [
            [&$x, &$x, true],
            [&$x, &$y, true],
            [&$x, &$z, false],
            [&$a, &$a, true],
            [&$a, &$b, false],
            [&$a, &$c, true],
            [&$a, &$d, false],
        ];
    }
}
