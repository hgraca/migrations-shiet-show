<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;
use Acme\PhpExtension\Exception\CsvException;

final class CsvHelper extends AbstractStaticClass
{
    public static function arrayRowToCsv(
        array $fields,
        string $separator = ',',
        string $enclosure = '"',
        string $escape = '\\'
    ): string {
        if (is_array(current($fields))) {
            throw new CsvException('Can only convert one dimensional arrays to CSV.');
        }

        $buffer = fopen('php://temp', 'r+');
        fputcsv($buffer, $fields, $separator, $enclosure, $escape);
        rewind($buffer);
        $csv = fgets($buffer);
        fclose($buffer);

        return $csv;
    }
}
