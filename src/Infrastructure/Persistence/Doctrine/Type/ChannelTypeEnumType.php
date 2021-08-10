<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\App\Core\Component\Channel\Domain\ChannelTypeEnum;

final class ChannelTypeEnumType extends AbstractEnumStringType
{
    protected function getMappedClass(): string
    {
        return ChannelTypeEnum::class;
    }
}
