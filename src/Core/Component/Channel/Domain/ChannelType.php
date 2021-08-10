<?php

declare(strict_types=1);

namespace Acme\App\Core\Component\Channel\Domain;

use Acme\App\Core\SharedKernel\Component\Channel\Domain\ChannelTypeId;

/**
 * @codeCoverageIgnore
 */
class ChannelType
{
    /** @var ChannelTypeId */
    private $id;

    /** @var ChannelTypeEnum */
    private $name;

    /** @var bool */
    private $enabled;

    public function __construct(ChannelTypeEnum $name)
    {
        $this->id = new ChannelTypeId();
        $this->name = $name;
        $this->enabled = false;
    }
}
