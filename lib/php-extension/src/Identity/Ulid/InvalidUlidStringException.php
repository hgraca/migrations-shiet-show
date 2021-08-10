<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity\Ulid;

use Acme\PhpExtension\Exception\CmRuntimeException;

final class InvalidUlidStringException extends CmRuntimeException
{
    public function __construct(string $ulid)
    {
        parent::__construct("Invalid Ulid string '$ulid'.");
    }
}
