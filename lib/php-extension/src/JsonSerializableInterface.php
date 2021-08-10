<?php

declare(strict_types=1);

namespace Acme\PhpExtension;

interface JsonSerializableInterface
{
    public function toJson(bool $prettyPrint = false): string;

    /**
     * @return static
     */
    public static function fromJson(string $json);
}
