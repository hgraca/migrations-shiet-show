<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\App\Core\Component\Channel\Domain\ChannelStateEnum;

final class ChannelStateEnumType extends AbstractEnumStringType
{
    protected function getMappedClass(): string
    {
        return ChannelStateEnum::class;
    }
}
