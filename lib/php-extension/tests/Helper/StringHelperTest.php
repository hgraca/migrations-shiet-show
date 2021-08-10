<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\StringHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class StringHelperTest extends AbstractTestCase
{
    /**
     * @test
     * @dataProvider provideStrings
     */
    public function contains_finds_needle_when_its_there(string $needle, string $haystack, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, StringHelper::contains($needle, $haystack));
    }

    public function provideStrings(): array
    {
        return [
            ['', 'beginning to ending', true],
            ['beginning', 'beginning to ending', true],
            ['to', 'beginning to ending', true],
            ['ending', 'beginning to ending', true],
            ['unexistent', 'beginning to ending', false],
        ];
    }

    /**
     * @test
     * @dataProvider stringContainsCharacterProvider
     */
    public function asserts_string_contains_only_given_characters(string $needle, string $haystack, bool $expectedOutcome): void
    {
        self::assertSame(
            $expectedOutcome,
            StringHelper::containsOnly($needle, $haystack),
            "Expecting only to find {$haystack} in {$needle} but found other characters as well"
        );
    }

    public function stringContainsCharacterProvider(): array
    {
        return [
            ['abcd', 'ab', false],
            ['aabbbccccdddd', 'abcd', true],
            ['this is a sentence', 'thisaentc ', true],
        ];
    }

    /**
     * @test
     * @dataProvider provideStudlyTests
     */
    public function to_studly_case(string $input, string $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::toStudlyCase($input));
    }

    public function provideStudlyTests(): array
    {
        return [
            ['TABLE_NAME', 'TableName'],
            ['Table_NaMe', 'TableNaMe'],
            ['table_name', 'TableName'],
            ['TableName', 'TableName'],
            ['tableName', 'TableName'],
            ['table-Name', 'TableName'],
            ['table.Name', 'TableName'],
            ['table Name', 'TableName'],
        ];
    }

    /**
     * @test
     * @dataProvider provideConstantTests
     */
    public function to_constant_case(string $input, string $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::toConstantCase($input));
    }

    public function provideConstantTests(): array
    {
        return [
            ['TABLE_NAME', 'TABLE_NAME'],
            ['Table_NaMe', 'TABLE_NA_ME'],
            ['table_name', 'TABLE_NAME'],
            ['TableName', 'TABLE_NAME'],
            ['tableName', 'TABLE_NAME'],
            ['table-Name', 'TABLE_NAME'],
            ['table.Name', 'TABLE_NAME'],
            ['table Name', 'TABLE_NAME'],
        ];
    }

    /**
     * @test
     * @dataProvider provideCamelCaseTests
     */
    public function to_camel_case(string $input, string $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::toCamelCase($input));
    }

    public function provideCamelCaseTests(): array
    {
        return [
            ['TABLE_NAME', 'tableName'],
            ['Table_NaMe', 'tableNaMe'],
            ['table_name', 'tableName'],
            ['TableName', 'tableName'],
            ['tableName', 'tableName'],
        ];
    }

    /**
     * @test
     * @dataProvider provideSnakeCaseTests
     */
    public function to_snake_case(string $input, string $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::toSnakeCase($input));
    }

    public function provideSnakeCaseTests(): array
    {
        return [
            ['TABLE_NAME', 'table_name'],
            ['Table_NaMe', 'table_na_me'],
            ['TableName', 'table_name'],
            ['table_Name', 'table_name'],
            ['tableName', 'table_name'],
        ];
    }

    /**
     * @test
     */
    public function get_random_string(): void
    {
        $stringLength = 7;
        $randomString1 = StringHelper::getRandomString($stringLength);
        self::assertEquals($stringLength, strlen($randomString1));

        $randomString2 = StringHelper::getRandomString($stringLength);
        self::assertEquals($stringLength, strlen($randomString2));

        self::assertNotSame($randomString1, $randomString2);
    }

    /**
     * @test
     */
    public function can_create_random_string_from_given_set(): void
    {
        $stringLength = 16;
        $maxIterations = 10;
        $allowedCharacters = 'ab';

        $randomStrings = [];

        for ($iteration = 0; $iteration < $maxIterations; ++$iteration) {
            $randomStrings[] = StringHelper::getRandomString($stringLength, $allowedCharacters);
        }

        $duplicationCount = count($randomStrings) - count(array_unique($randomStrings));
        self::assertSame(
            0,
            $duplicationCount,
            "There should be no duplicated strings, {$duplicationCount} duplicates found in {$maxIterations} attempts"
        );

        $this->assertStringOnlyContainsChars(
            $randomStrings[random_int(0, count($randomStrings) - 1)],
            $allowedCharacters
        );
    }

    private function assertStringOnlyContainsChars(string $string, string $allowedChars): void
    {
        $allowedCharList = array_unique(str_split($allowedChars));
        $charsInString = array_unique(str_split($string));

        self::assertCount(
            0,
            array_diff($charsInString, $allowedCharList),
            "The string '{$string}' may only contain characters [" . implode(', ', $allowedCharList) . ']'
        );
    }

    /**
     * @test
     */
    public function can_shuffle_a_string(): void
    {
        $unshuffled = 'hello world';
        $shuffled = StringHelper::shuffle($unshuffled);

        self::assertNotSame(
            $unshuffled,
            $shuffled,
            'The shuffled string should not be idential to the unshuffled string'
        );

        self::assertSame(
            strlen($shuffled),
            strlen($unshuffled),
            'The shuffled strings should be of even length as the unshuffled string'
        );
    }

    /**
     * @test
     *
     * @dataProvider provideStartsWithTests
     */
    public function starts_with(string $needle, string $haystack, bool $caseSensitive, bool $expectedOutput): void
    {
        self::assertSame($expectedOutput, StringHelper::startsWith($needle, $haystack, $caseSensitive));
    }

    public function provideStartsWithTests(): array
    {
        return [
            ['start', 'starts with it', true, true],
            ['start', 'starts with it', false, true],
            ['Start', 'starts with it but has different case', true, false],
            ['Start', 'starts with it', false, true],
            ['bla', 'starts with it', true, false],
            ['bla', 'starts with it', false, false],
            ['Bla', 'starts with it but has different case', true, false],
            ['Bla', 'starts with it', false, false],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideBase64UrlSafeEncode
     */
    public function can_encode_as_url_safe_base64(string $toEncode, string $encoded): void
    {
        self::assertSame(
            $encoded,
            StringHelper::base64_url_encode($toEncode)
        );
    }

    public function provideBase64UrlSafeEncode()
    {
        return [
            ['string to encode', 'c3RyaW5nIHRvIGVuY29kZQ--'],
            ['encoded string', 'ZW5jb2RlZCBzdHJpbmc-'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideBase64UrlSafeDecode
     */
    public function can_decode_from_url_safe_base64(string $toDecode, string $decoded): void
    {
        self::assertSame(
            $decoded,
            StringHelper::base64_url_decode($toDecode)
        );
    }

    public function provideBase64UrlSafeDecode()
    {
        return [
            ['c3RyaW5nIHRvIGVuY29kZQ--', 'string to encode'],
            ['ZW5jb2RlZCBzdHJpbmc-', 'encoded string'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideEnsureWrappedWithStringCases
     */
    public function ensure_wrapped_with_string(string $left, string $center, string $right, string $expected): void
    {
        self::assertSame($expected, StringHelper::ensureWrappedWithString($left, $right, $center));
    }

    public function provideEnsureWrappedWithStringCases(): array
    {
        return [
            ['aaa', 'bbb', 'ccc', 'aaabbbccc'],
            ['aaa', 'aaabbb', 'ccc', 'aaabbbccc'],
            ['aaa', 'bbbccc', 'ccc', 'aaabbbccc'],
            ['aaa', 'aaabbbccc', 'ccc', 'aaabbbccc'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideLeftStringTrimCases
     */
    public function left_string_trim(string $trim, string $haystack, string $replacement, string $expected): void
    {
        if ($replacement === '') {
            self::assertSame($expected, StringHelper::leftStringReplace($trim, $haystack));
        } else {
            self::assertSame($expected, StringHelper::leftStringReplace($trim, $haystack, $replacement));
        }
    }

    public function provideLeftStringTrimCases(): array
    {
        return [
            ['aaa', 'aaabbb', '', 'bbb'],
            ['bbb', 'aaabbb', '', 'aaabbb'],
            ['aaa', 'aaabbb', 'zzz', 'zzzbbb'],
            ['bbb', 'aaabbb', 'zzz', 'aaabbb'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideRightStringTrimCases
     */
    public function right_string_trim(string $trim, string $haystack, string $replacement, string $expected): void
    {
        if ($replacement === '') {
            self::assertSame($expected, StringHelper::rightStringReplace($trim, $haystack));
        } else {
            self::assertSame($expected, StringHelper::rightStringReplace($trim, $haystack, $replacement));
        }
    }

    public function provideRightStringTrimCases(): array
    {
        return [
            ['aaa', 'aaabbb', '', 'aaabbb'],
            ['bbb', 'aaabbb', '', 'aaa'],
            ['aaa', 'aaabbb', 'zzz', 'aaabbb'],
            ['bbb', 'aaabbb', 'zzz', 'aaazzz'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideCapitalizedWordCases
     */
    public function can_turn_different_cases_to_human_readable(string $input, string $expected): void
    {
        self::assertSame($expected, StringHelper::toHumanReadable($input));
    }

    public function provideCapitalizedWordCases(): array
    {
        return [
            ['ThisIsAStringOfWords', 'This is a string of words'],
            ['This_Is_A_String_Of_Words', 'This is a string of words'],
            ['this-is-a-string-of-words', 'This is a string of words'],
            ['camelCasingWorksAsWell', 'Camel casing works as well'],
        ];
    }
}
