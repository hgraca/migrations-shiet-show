<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Exception;

use Acme\PhpExtension\Helper\ClassHelper;
use Throwable;

trait CmExceptionTrait
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: ClassHelper::extractCanonicalClassName(static::class), $code, $previous);
    }
}
