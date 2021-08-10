<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

abstract class AbstractUlidType extends StringType
{
    use TypeTrait;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        $column['length'] = 26;
        $column['fixed'] = true;

        return $platform->getVarcharTypeDeclarationSQL($column);
    }
}
