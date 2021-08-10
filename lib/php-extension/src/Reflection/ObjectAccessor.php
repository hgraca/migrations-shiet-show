<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Reflection;

use Acme\PhpExtension\AbstractStaticClass;
use Acme\PhpExtension\Exception\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use function array_merge;
use function array_reverse;
use function array_values;

final class ObjectAccessor extends AbstractStaticClass
{
    public static function setPropertiesFromKeyValue(object $object, array $propertyList): void
    {
        $hierarchyPropertyList = self::getAllHierarchyReflectionProperties($object);
        foreach ($hierarchyPropertyList as $propertyName => $reflectionProperty) {
            $reflectionProperty->setAccessible(true);

            if (array_key_exists($propertyName, $propertyList)) {
                $property = &$propertyList[$propertyName];
            } elseif (self::isPropertyNullable($reflectionProperty)) {
                $property = null;
            } else {
                $class = get_class($object);
                throw new InvalidArgumentException(
                    "The target object, of class '$class', "
                    . "requires property '$propertyName', "
                    . "which is not in the given property list: \n"
                    . print_r($propertyList, true)
                );
            }

            $reflectionProperty->setValue($object, $property); // This doesn't set by reference, but for the future...
            unset($propertyList[$propertyName]);
        }
    }

    public static function getPropertiesAsKeyValue(object $object): array
    {
        return self::getAllHierarchyPropertiesAsKeyValue($object);
    }

    private static function getAllHierarchyPropertiesAsKeyValue(object $object): array
    {
        $propertyList = [];
        self::visitHierarchyProperties(
            $object,
            static function (ReflectionProperty $reflectionProperty) use (&$propertyList, $object): void {
                $propertyList[$reflectionProperty->getDeclaringClass()->getName()][$reflectionProperty->getName()]
                    = $reflectionProperty->getValue($object);
            }
        );

        return !empty($propertyList) ? array_merge(...array_values(array_reverse($propertyList))) : [];
    }

    /**
     * @return ReflectionProperty[]
     */
    private static function getAllHierarchyReflectionProperties(object $object): array
    {
        $propertyList = [];
        self::visitHierarchyProperties(
            $object,
            static function (ReflectionProperty $reflectionProperty) use (&$propertyList): void {
                $propertyList[$reflectionProperty->getDeclaringClass()->getName()][$reflectionProperty->getName()] =
                    $reflectionProperty;
            }
        );

        return !empty($propertyList) ? array_merge(...array_values(array_reverse($propertyList))) : [];
    }

    private static function visitHierarchyProperties(object $object, callable $visitProperty): void
    {
        try {
            $reflectionClass = new ReflectionClass($object);
            do {
                foreach ($reflectionClass->getProperties() as $reflectionProperty) {
                    $reflectionProperty->setAccessible(true);
                    $visitProperty($reflectionProperty);
                }
            } while ($reflectionClass = $reflectionClass->getParentClass());
        } catch (ReflectionException $e) {
            // Silently ignore this exception so the visiting can continue. Maybe we should log a warning though.
        }
    }

    private static function isPropertyNullable(ReflectionProperty $reflectionProperty): bool
    {
        $docBlock = $reflectionProperty->getDocComment();

        if ($docBlock === false) {
            return false;
        }

        return preg_match('/(\* @var \?|\* @var null\||\* @var .+\|null)/mi', $docBlock) === 1;
    }
}
