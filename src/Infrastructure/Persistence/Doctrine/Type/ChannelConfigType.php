<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\App\Core\Component\Channel\Domain\ChannelConfig;

final class ChannelConfigType extends AbstractJsonType
{
    protected function getMappedClass(): string
    {
        return ChannelConfig::class;
    }
}
