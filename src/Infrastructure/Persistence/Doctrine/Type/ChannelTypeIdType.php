<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\App\Core\SharedKernel\Component\Channel\Domain\ChannelTypeId;

final class ChannelTypeIdType extends AbstractUlidType
{
    protected function getMappedClass(): string
    {
        return ChannelTypeId::class;
    }
}
