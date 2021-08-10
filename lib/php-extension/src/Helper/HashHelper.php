<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;

final class HashHelper extends AbstractStaticClass
{
    public static function hashString(string $data): string
    {
        /** @var array{GT_APP_SECRET: string} $_ENV */
        $salt = $_ENV['GT_APP_SECRET'];

        if (empty($salt)) {
            throw new SecretKeyNotSetException();
        }

        return hash('sha256', $data . $salt);
    }

    public static function isSha256(string $hashedString): bool
    {
        return (bool) preg_match('/^[0-9a-f]{64}$/i', $hashedString);
    }
}
