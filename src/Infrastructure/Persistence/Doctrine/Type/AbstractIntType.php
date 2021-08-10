<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Types\IntegerType;

abstract class AbstractIntType extends IntegerType
{
    use TypeTrait;
}
