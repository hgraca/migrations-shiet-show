<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\Exception\CmRuntimeException;

final class SecretKeyNotSetException extends CmRuntimeException
{
}
