<?php

declare(strict_types=1);

namespace Acme\App\Core\Component\Channel\Domain;

use Acme\PhpExtension\Enum\AbstractEnum;

/**
 * @method static ChannelStateEnum enabled()
 * @method bool isEnabled()
 * @method static ChannelStateEnum disabled()
 * @method bool isDisabled()
 */
final class ChannelStateEnum extends AbstractEnum
{
    public const ENABLED = 'ENABLED';
    public const DISABLED = 'DISABLED';
}
