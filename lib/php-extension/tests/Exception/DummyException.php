<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Exception;

use Acme\PhpExtension\Exception\CmExceptionInterface;
use Acme\PhpExtension\Exception\CmExceptionTrait;
use LogicException;

class DummyException extends LogicException implements CmExceptionInterface
{
    use CmExceptionTrait;
}
