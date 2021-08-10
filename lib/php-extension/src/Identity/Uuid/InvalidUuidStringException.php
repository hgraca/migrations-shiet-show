<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity\Uuid;

use Acme\PhpExtension\Exception\CmLogicException;

final class InvalidUuidStringException extends CmLogicException
{
    public function __construct(string $uuid)
    {
        parent::__construct("Invalid Uuid string '$uuid'.");
    }
}
