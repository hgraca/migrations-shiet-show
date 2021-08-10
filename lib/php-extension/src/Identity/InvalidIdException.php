<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity;

use Acme\PhpExtension\Exception\CmLogicException;

final class InvalidIdException extends CmLogicException
{
    public function __construct($id)
    {
        parent::__construct(sprintf('Invalid id [%s]', $id));
    }
}
