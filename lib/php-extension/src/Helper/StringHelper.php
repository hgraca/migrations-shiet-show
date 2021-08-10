<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;

final class StringHelper extends AbstractStaticClass
{
    private const ALLOWED_RANDOM_CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Returns true if this string contains with the given needle.
     * If the needle is empty, it always returns true.
     */
    public static function contains(string $needle, string $haystack): bool
    {
        return $needle === '' || mb_strpos($haystack, $needle) !== false;
    }

    public static function containsOnly(string $needle, string $allowedCharacters): bool
    {
        $allowedCharactersList = array_unique(str_split($allowedCharacters));
        $charsInString = array_unique(str_split($needle));

        return count(array_diff($charsInString, $allowedCharactersList)) === 0;
    }

    public static function toStudlyCase(string $sentence): string
    {
        return self::removeAllSpaces(
            self::makeAllWordsUpperCaseFirst(
                self::makeLowCase(
                    self::separateCapitalizedWordsWithSpace(
                        self::separateWordsWithSpace($sentence)
                    )
                )
            )
        );
    }

    public static function toCamelCase(string $sentence): string
    {
        return lcfirst(self::toStudlyCase($sentence));
    }

    public static function toSnakeCase(string $sentence): string
    {
        $sentence = self::toStudlyCase($sentence);

        $replace = '$1' . '_' . '$2';

        return ctype_lower($sentence) ? $sentence : mb_strtolower(preg_replace('/(.)([A-Z])/', $replace, $sentence));
    }

    public static function toConstantCase(string $sentence): string
    {
        return strtoupper(self::toSnakeCase($sentence));
    }

    public static function startsWith(string $needle, string $haystack, bool $caseSensitive = true): bool
    {
        return $caseSensitive ? strpos($haystack, $needle) === 0 : stripos($haystack, $needle) === 0;
    }

    public static function getRandomString(int $length = 5, string $characterSet = self::ALLOWED_RANDOM_CHARS): string
    {
        $randomString = '';

        for ($i = 0; $i < $length; ++$i) {
            $index = random_int(0, strlen($characterSet) - 1);
            $randomString .= $characterSet[$index];
        }

        return $randomString;
    }

    public static function shuffle(string $input): string
    {
        $splitStr = str_split($input);
        $shuffledString = '';

        do {
            $index = random_int(0, count($splitStr) - 1);
            $shuffledString .= $splitStr[$index];
            array_splice($splitStr, $index, 1);
        } while (strlen($shuffledString) < strlen($input));

        return $shuffledString;
    }

    public static function base64_url_encode(string $input): string
    {
        return strtr(base64_encode($input), '+/=', '._-');
    }

    public static function base64_url_decode(string $input): string
    {
        return base64_decode(strtr($input, '._-', '+/='));
    }

    public static function ensureWrappedWithString(string $leftEdge, string $rightEdge, string $haystack): string
    {
        return $leftEdge
               . self::leftStringReplace($leftEdge, self::rightStringReplace($rightEdge, $haystack))
               . $rightEdge;
    }

    public static function leftStringReplace(string $trim, string $haystack, string $replacement = ''): string
    {
        $trim = preg_quote($trim, '/');

        return preg_replace("/^$trim/s", $replacement, $haystack);
    }

    public static function rightStringReplace(string $trim, string $haystack, string $replacement = ''): string
    {
        $trim = preg_quote($trim, '/');

        return preg_replace("/$trim$/s", $replacement, $haystack);
    }

    public static function toHumanReadable(string $input): string
    {
        return ucfirst(
            self::makeLowCase(
                ltrim(
                    preg_replace(
                    '/(?<!\ )[A-Z]/',
                    ' $0',
                    self::separateWordsWithSpace($input)
                    )
                )
            )
        );
    }

    /**
     * @param array<string> $wordSeparatorList
     */
    private static function separateWordsWithSpace(
        string $sentence,
        array $wordSeparatorList = ['-', '_', '.', ' ']
    ): string {
        return str_replace($wordSeparatorList, ' ', $sentence);
    }

    private static function makeAllWordsUpperCaseFirst(
        string $sentence,
        array $wordSeparatorList = ['-', '_', '.', ' ']
    ): string {
        return ucwords($sentence, implode('', $wordSeparatorList));
    }

    private static function makeLowCase(string $sentence): string
    {
        return mb_strtolower($sentence);
    }

    private static function removeAllSpaces(string $sentence): string
    {
        return str_replace([' '], '', $sentence);
    }

    private static function separateCapitalizedWordsWithSpace(string $sentence): string
    {
        return preg_replace('/([a-z])([A-Z])/', '$1 $2', $sentence);
    }
}
