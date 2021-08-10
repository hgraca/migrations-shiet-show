<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Type;

use Acme\PhpExtension\JsonSerializableInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

abstract class AbstractJsonType extends JsonType
{
    use TypeTrait;

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        return $this->createSpecificObject($value);
    }

    protected function createSpecificObject($value)
    {
        /** @var string|JsonSerializableInterface $class */
        $class = $this->getMappedClass();

        return $class::fromJson($value);
    }
}
