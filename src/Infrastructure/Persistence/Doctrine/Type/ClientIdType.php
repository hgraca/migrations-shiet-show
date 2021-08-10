<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\App\Core\SharedKernel\Component\Client\Domain\ClientId;

final class ClientIdType extends AbstractIntType
{
    protected function getMappedClass(): string
    {
        return ClientId::class;
    }
}
