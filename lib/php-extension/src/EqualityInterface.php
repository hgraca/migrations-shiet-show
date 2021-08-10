<?php

declare(strict_types=1);

namespace Acme\PhpExtension;

interface EqualityInterface
{
    /**
     * @param static $object
     */
    public function equals($object): bool;
}
