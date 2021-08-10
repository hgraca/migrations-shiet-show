<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Reflection;

use Acme\PhpExtension\AbstractStaticClass;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use function array_merge;
use function count;
use function get_class;

final class ClassAccessor extends AbstractStaticClass
{
    /**
     * @throws ReflectionException
     *
     * @return mixed
     */
    public static function invokeProtectedMethod($object, string $methodName, array $arguments = [])
    {
        $class = new ReflectionClass(get_class($object));
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
    }

    public static function setProtectedProperty($object, string $propertyName, $value): void
    {
        $class = new ReflectionClass(get_class($object));

        $property = static::getReflectionProperty($class, $propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    public static function getProtectedProperty($object, string $propertyName)
    {
        $class = new ReflectionClass(get_class($object));

        $property = static::getReflectionProperty($class, $propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @return ReflectionProperty[]
     */
    public static function getClassPropertyList(string $className): array
    {
        $reflectionClass = new ReflectionClass($className);
        $reflectionPropertyList = $reflectionClass->getProperties();
        $finalReflectionPropertyList = [];
        foreach ($reflectionPropertyList as $reflectionProperty) {
            $finalReflectionPropertyList[$reflectionProperty->getName()] = $reflectionProperty;
        }
        if ($parentClass = $reflectionClass->getParentClass()) {
            $parent_props_arr = self::getClassPropertyList($parentClass->getName());
            if (count($parent_props_arr) > 0) {
                $finalReflectionPropertyList = array_merge($parent_props_arr, $finalReflectionPropertyList);
            }
        }

        return $finalReflectionPropertyList;
    }

    public static function getClassPropertyListAsStringList(string $className): array
    {
        $reflectionPropertiesList = self::getClassPropertyList($className);

        $finalReflectionPropertyList = [];

        foreach ($reflectionPropertiesList as $reflectionProperty) {
            $finalReflectionPropertyList[] = $reflectionProperty->getName();
        }

        return $finalReflectionPropertyList;
    }

    public static function getStaticPropertyValue(string $classFqcn, string $propertyName)
    {
        $class = new ReflectionClass($classFqcn);
        $staticProperties = $class->getStaticProperties();

        return $staticProperties[$propertyName];
    }

    public static function setStaticPropertyValue(string $classFqcn, string $propertyName, $value): void
    {
        $class = new ReflectionClass($classFqcn);
        $reflectedProperty = $class->getProperty($propertyName);
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($value);
    }

    /**
     * @return \ReflectionClassConstant[]
     */
    public static function getClassConstantList(string $className): array
    {
        return (new ReflectionClass($className))
            ->getConstants();
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $classFqcn
     *
     * @throws ReflectionException
     *
     * @return T
     */
    public static function instantiateWithoutConstructor(string $classFqcn)
    {
        $class = new ReflectionClass($classFqcn);

        return $class->newInstanceWithoutConstructor();
    }

    private static function getReflectionProperty(ReflectionClass $class, string $propertyName): ReflectionProperty
    {
        try {
            return $class->getProperty($propertyName);
        } catch (ReflectionException $e) {
            $parentClass = $class->getParentClass();
            if ($parentClass === false) {
                throw $e;
            }

            return static::getReflectionProperty($parentClass, $propertyName);
        }
    }
}
