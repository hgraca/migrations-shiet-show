<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Types\StringType;

/**
 * This class is here as a mapper for an AbstractStringId, which is useful, for example when our application uses
 * 3rd party systems that provide us with their own (string) IDs, and which we need to store on our system.
 */
abstract class AbstractStringType extends StringType
{
    use TypeTrait;
}
