<?php

declare(strict_types=1);

namespace Acme\App\Core\Component\Channel\Domain;

use Acme\PhpExtension\Enum\AbstractEnum;

/**
 * @method static ChannelTypeEnum ticketStreet()
 * @method bool isTicketStreet()
 * @method static ChannelTypeEnum googleThingsToDo()
 * @method bool isGoogleThingsToDo()
 */
final class ChannelTypeEnum extends AbstractEnum
{
    public const TICKET_STREET = 'ticket_street';
    public const GOOGLE_THINGS_TO_DO = 'google_things_to_do';
}
