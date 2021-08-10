<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

final class JsonHelper
{
    public static function encode($data, int $flags = 0, int $depth = 512): string
    {
        $encoded = json_encode($data, $flags, $depth);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_RECURSION:
                throw new JsonEncodingException('One or more recursive references in the value to be encoded');
            case JSON_ERROR_INF_OR_NAN:
                throw new JsonEncodingException('One or more NAN or INF values in the value to be encoded');
            case JSON_ERROR_UNSUPPORTED_TYPE:
                throw new JsonEncodingException('A value of a type that cannot be encoded was given');
            case JSON_ERROR_INVALID_PROPERTY_NAME:
                throw new JsonEncodingException('A property name that cannot be encoded was given');
            case JSON_ERROR_UTF16:
                throw new JsonEncodingException('Malformed UTF-16 characters, possibly incorrectly encoded');
            default:
                throw new JsonEncodingException('Unknown error');
        }
    }

    /**
     * @throws JsonDecodingException
     */
    public static function decode(string $json, bool $assoc = true, int $options = 0)
    {
        $decoded = json_decode($json, $assoc, 512, $options);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $decoded;
            case JSON_ERROR_DEPTH:
                throw new JsonDecodingException("Maximum stack depth exceeded:\n'{$json}'");
            case JSON_ERROR_STATE_MISMATCH:
                throw new JsonDecodingException("Underflow or the modes mismatch:\n'{$json}'");
            case JSON_ERROR_CTRL_CHAR:
                throw new JsonDecodingException("Unexpected control character found:\n'{$json}'");
            case JSON_ERROR_SYNTAX:
                throw new JsonDecodingException("Syntax error, malformed JSON:\n'{$json}'");
            case JSON_ERROR_UTF8:
                throw new JsonDecodingException("Malformed UTF-8 characters, possibly incorrectly encoded:\n'{$json}'");
            default:
                throw new JsonDecodingException("Unknown error:\n'{$json}'");
        }
    }

    public static function isJson(string $string): bool
    {
        if (!StringHelper::startsWith('[', $string) && !StringHelper::startsWith('{', $string)) {
            return false;
        }

        /** @psalm-suppress UnusedFunctionCall */
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
