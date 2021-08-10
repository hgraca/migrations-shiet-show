<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Types\GuidType;

/**
 * It is preferable to use the AbstractBinaryUuidType, because the querying will be faster and the space taken will
 * be less.
 * However, if you need to look at the DB and see the actual UUID there, this is the mapper you should use.
 */
abstract class AbstractUuidType extends GuidType
{
    use TypeTrait;
}
