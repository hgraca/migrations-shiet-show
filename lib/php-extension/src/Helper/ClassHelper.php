<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;

final class ClassHelper extends AbstractStaticClass
{
    public static function extractCanonicalClassName(string $classFqcn): string
    {
        return mb_substr($classFqcn, (int) mb_strrpos($classFqcn, '\\') + 1);
    }

    public static function extractCanonicalMethodName(string $methodFqcn): string
    {
        return mb_substr($methodFqcn, (int) mb_strrpos($methodFqcn, '::') + 2);
    }
}
