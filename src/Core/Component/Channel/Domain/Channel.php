<?php

declare(strict_types=1);

namespace Acme\App\Core\Component\Channel\Domain;

use Acme\App\Core\SharedKernel\Component\Channel\Domain\ChannelId;
use Acme\App\Core\SharedKernel\Component\Client\Domain\ClientId;

class Channel
{
    /** @var ChannelId */
    private $id;

    /** @var ClientId */
    private $clientId;

    /** @var ChannelConfig */
    private $config;

    /** @var ChannelType */
    private $channelType;

    /** @var ChannelStateEnum */
    private $stateEnum;

    public function __construct(ClientId $clientId, ChannelConfig $config, ChannelType $channelType)
    {
        $this->id = new ChannelId();
        $this->clientId = $clientId;
        $this->config = $config;
        $this->channelType = $channelType;
        $this->stateEnum = ChannelStateEnum::disabled();
    }

    public function getId(): ChannelId
    {
        return $this->id;
    }
}
