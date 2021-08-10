<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Reflection;

use Acme\PhpExtension\Exception\InvalidArgumentException;
use Acme\PhpExtension\Reflection\ObjectAccessor;
use Acme\PhpExtension\Test\AbstractTestCase;
use Acme\PhpExtension\Test\Reflection\TestSubjects\ChildClass;
use Acme\PhpExtension\Test\Reflection\TestSubjects\ChildClassFromAbstractParent;
use Acme\PhpExtension\Test\Reflection\TestSubjects\FinalChildClass;
use Acme\PhpExtension\Test\Reflection\TestSubjects\FinalChildClassFromAbstractParent;

/**
 * @internal
 *
 * @small
 */
final class ObjectAccessorTest extends AbstractTestCase
{
    /**
     * @test
     *
     * @dataProvider provideClassNames
     */
    public function set_properties_from_key_value(string $className): void
    {
        $properties = [
            'parentPrivate' => 'a',
            'parentProtected' => 'b',
            'parentPublic' => null,
            'childPrivate' => 'd',
            'childProtected' => 'e',
            'childPublic' => 'f',
            'parentPrivateStatic' => 'g',
            'childPrivateStatic' => 'h',
        ];

        /** @var ChildClass|FinalChildClass|ChildClassFromAbstractParent|FinalChildClassFromAbstractParent $object */
        $object = new $className();

        ObjectAccessor::setPropertiesFromKeyValue($object, $properties);

        self::assertEquals($properties['parentPrivate'], $object->getParentPrivate());
        self::assertEquals($properties['parentProtected'], $object->getParentProtected());
        self::assertEquals($properties['parentPublic'], $object->getParentPublic());
        self::assertEquals($properties['childPrivate'], $object->getChildPrivate());
        self::assertEquals($properties['childProtected'], $object->getChildProtected());
        self::assertEquals($properties['childPublic'], $object->getChildPublic());
        self::assertEquals($properties['parentPrivateStatic'], $object->getParentPrivateStatic());
        self::assertEquals($properties['childPrivateStatic'], $object->getChildPrivateStatic());
    }

    /**
     * @test
     *
     * @dataProvider provideClassNamesWithNullableProperty
     */
    public function set_property_null_if_not_provided_and_is_nullable(string $className): void
    {
        $properties = [
            'parentPrivate' => 'a',
            'parentProtected' => 'b',
            'parentPublic' => 'c',
            'childPrivate' => 'd',
            'childProtected' => 'e',
            'childPublic' => 'f',
            'parentPrivateStatic' => 'g',
        ];

        /** @var ChildClass|FinalChildClass|ChildClassFromAbstractParent|FinalChildClassFromAbstractParent $object */
        $object = new $className();

        ObjectAccessor::setPropertiesFromKeyValue($object, $properties);

        self::assertEquals($properties['parentPrivate'], $object->getParentPrivate());
        self::assertEquals($properties['parentProtected'], $object->getParentProtected());
        self::assertEquals($properties['parentPublic'], $object->getParentPublic());
        self::assertEquals($properties['childPrivate'], $object->getChildPrivate());
        self::assertEquals($properties['childProtected'], $object->getChildProtected());
        self::assertEquals($properties['childPublic'], $object->getChildPublic());
        self::assertEquals($properties['parentPrivateStatic'], $object->getParentPrivateStatic());
        self::assertEquals(null, $object->getChildPrivateStatic());
    }

    /**
     * @test
     */
    public function throws_exception_if_property_not_provided_and_not_nullable(): void
    {
        $className = ChildClass::class;

        $properties = [
            'parentPrivate' => 'a',
            'parentProtected' => 'b',
            'parentPublic' => 'c',
            'childPrivate' => 'd',
            'childProtected' => 'e',
            'childPublic' => 'f',
            'parentPrivateStatic' => 'g',
        ];

        /** @var ChildClass|FinalChildClass|ChildClassFromAbstractParent|FinalChildClassFromAbstractParent $object */
        $object = new $className();

        $this->expectException(InvalidArgumentException::class);
        ObjectAccessor::setPropertiesFromKeyValue($object, $properties);
    }

    /**
     * @test
     *
     * @dataProvider provideClassNames
     */
    public function get_properties_as_key_value(string $className): void
    {
        /** @var ChildClass|FinalChildClass|ChildClassFromAbstractParent|FinalChildClassFromAbstractParent $object */
        $object = new $className('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');

        $actualProperties = ObjectAccessor::getPropertiesAsKeyValue($object);

        self::assertEquals($object->getParentPrivate(), $actualProperties['parentPrivate']);
        self::assertEquals($object->getParentProtected(), $actualProperties['parentProtected']);
        self::assertEquals($object->getParentPublic(), $actualProperties['parentPublic']);
        self::assertEquals($object->getChildPrivate(), $actualProperties['childPrivate']);
        self::assertEquals($object->getChildProtected(), $actualProperties['childProtected']);
        self::assertEquals($object->getChildPublic(), $actualProperties['childPublic']);
        self::assertEquals($object->getParentPrivateStatic(), $actualProperties['parentPrivateStatic']);
        self::assertEquals($object->getChildPrivateStatic(), $actualProperties['childPrivateStatic']);
    }

    public function provideClassNames(): array
    {
        return [
            [ChildClass::class],
            [FinalChildClass::class],
            [ChildClassFromAbstractParent::class],
            [FinalChildClassFromAbstractParent::class],
        ];
    }

    public function provideClassNamesWithNullableProperty(): array
    {
        return [
            [FinalChildClass::class],
            [ChildClassFromAbstractParent::class],
            [FinalChildClassFromAbstractParent::class],
        ];
    }
}
