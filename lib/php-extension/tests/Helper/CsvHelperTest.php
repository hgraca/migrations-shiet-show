<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Exception\CsvException;
use Acme\PhpExtension\Helper\CsvHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class CsvHelperTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function it_converts_a_single_dimension_array(): void
    {
        $row = ['col_1' => 1, 'col_2' => 'b', 'col_3' => 'something something', 'col_4' => true];

        self::assertEquals(
            "1,b,\"something something\",1\n",
            CsvHelper::arrayRowToCsv($row)
        );
    }

    /**
     * @test
     */
    public function it_throws_exception_when_trying_to_convert_multi_dimensional_arrays(): void
    {
        self::expectException(CsvException::class);
        self::expectExceptionMessage('Can only convert one dimensional arrays to CSV.');
        CsvHelper::arrayRowToCsv(
            [
                ['col_1' => 1, 'col_2' => 'b', 'col_3' => true],
                ['col_1' => 2, 'col_2' => 'c', 'col_3' => false],
            ]
        );
    }
}
