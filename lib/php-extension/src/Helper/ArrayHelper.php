<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;
use Acme\PhpExtension\Exception\OutOfBoundsException;

class ArrayHelper extends AbstractStaticClass
{
    public static function isKeyValueStringMap(array $array): bool
    {
        return self::hasOnlyStringKeys($array) && self::hasOnlyStringValues($array);
    }

    public static function hasOnlyStringKeys(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) === count($array);
    }

    public static function hasOnlyStringValues(array $array): bool
    {
        return count(array_filter(array_values($array), 'is_string')) === count($array);
    }

    public static function filterByKeys(array $hayStack, array $allowedKeys): array
    {
        return array_intersect_key($hayStack, array_flip($allowedKeys));
    }

    public static function ensureAllStringsAreWrappedIn(
        string $leftEdge,
        string $rightEdge,
        array $list,
        callable $shouldSkip = null
    ): array {
        $shouldSkip = $shouldSkip ?? [ArrayHelper::class, 'defaultShouldSkip'];

        return self::arrayMapRecursive(
            $list,
            /** @param mixed $value */
            function ($value) use ($leftEdge, $rightEdge, $shouldSkip) {
                return !is_string($value) || $shouldSkip($value)
                    ? $value
                    : StringHelper::ensureWrappedWithString($leftEdge, $rightEdge, $value);
            }
        );
    }

    /**
     * @template TKey as array-key
     * @template TValue
     *
     * @param array<TKey, TValue> $array
     */
    public static function arrayMapRecursive(array $array, callable $callback): array
    {
        $out = [];
        foreach ($array as $key => $val) {
            $out[$key] = is_array($val)
                ? self::arrayMapRecursive($val, $callback)
                : call_user_func($callback, $val);
        }

        return $out;
    }

    /**
     * @template T
     *
     * @param T $value
     */
    public static function set(
        array $array,
        string $path,
        $value,
        bool $createPath = false,
        string $separator = DIRECTORY_SEPARATOR
    ): array {
        $currentPath = &$array;
        $pathAsArray = array_filter(explode($separator, $path));
        $lastKey = array_pop($pathAsArray);
        foreach ($pathAsArray as $key) {
            if (!isset($currentPath[$key])) {
                if ($createPath) {
                    $currentPath[$key] = [];
                } else {
                    throw new OutOfBoundsException("Path '$path' does not exist in the array.");
                }
            }
            /** @var array $currentPath */
            $currentPath = &$currentPath[$key];
        }

        $currentPath[$lastKey] = $value;

        return $array;
    }

    public static function unset(
        array $array,
        string $path,
        string $separator = DIRECTORY_SEPARATOR
    ): array {
        $currentPath = &$array;
        $pathAsArray = array_filter(explode($separator, $path));
        $lastKey = array_pop($pathAsArray);
        foreach ($pathAsArray as $key) {
            if (!isset($currentPath[$key])) {
                throw new OutOfBoundsException("Path '$path' does not exist in the array.");
            }
            /** @var array $currentPath */
            $currentPath = &$currentPath[$key];
        }

        unset($currentPath[$lastKey]);

        return $array;
    }

    public static function has(
        array $array,
        string $path,
        string $separator = DIRECTORY_SEPARATOR
    ): bool {
        $currentPath = &$array;
        $pathAsArray = array_filter(explode($separator, $path));
        $lastKey = array_pop($pathAsArray);
        foreach ($pathAsArray as $key) {
            if (!isset($currentPath[$key])) {
                return false;
            }
            /** @var array $currentPath */
            $currentPath = &$currentPath[$key];
        }

        return isset($currentPath[$lastKey]);
    }

    /**
     * @return mixed
     */
    public static function get(array $array, string $path, string $separator = DIRECTORY_SEPARATOR)
    {
        $currentPath = &$array;
        $pathAsArray = array_filter(explode($separator, $path));
        $lastKey = array_pop($pathAsArray);
        foreach ($pathAsArray as $key) {
            if (!isset($currentPath[$key])) {
                throw new OutOfBoundsException("Path '$path' does not exist in the array.");
            }
            /** @var array $currentPath */
            $currentPath = &$currentPath[$key];
        }

        return $currentPath[$lastKey];
    }

    private static function defaultShouldSkip(): bool
    {
        return false;
    }
}
