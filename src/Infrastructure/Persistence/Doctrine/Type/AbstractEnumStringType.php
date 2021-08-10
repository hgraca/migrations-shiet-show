<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\PhpExtension\Enum\AbstractEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

abstract class AbstractEnumStringType extends StringType
{
    use TypeTrait;

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return (string) $value;
    }

    protected function createSpecificObject($value)
    {
        /** @var string|AbstractEnum $class */
        $class = $this->getMappedClass();

        return $class::hydrate($value);
    }
}
