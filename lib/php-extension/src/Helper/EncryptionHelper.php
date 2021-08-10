<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;

final class EncryptionHelper extends AbstractStaticClass
{
    public static function isSha1(string $string): bool
    {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $string);
    }
}
