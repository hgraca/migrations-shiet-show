<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Reflection;

use Acme\PhpExtension\Reflection\ClassAccessor;
use Acme\PhpExtension\Test\AbstractTestCase;
use Acme\PhpExtension\Test\Reflection\TestSubjects\DummyClass;
use ReflectionException;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class ClassAccessorTest extends AbstractTestCase
{
    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function get_protected_property_from_object_class(): void
    {
        $value = 7;
        $object = new DummyClass($value);

        self::assertSame($value, ClassAccessor::getProtectedProperty($object, 'var'));
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function get_protected_property_from_object_parent_class(): void
    {
        $value = 7;
        $parentValue = 19;
        $object = new DummyClass($value, $parentValue);

        self::assertSame($parentValue, ClassAccessor::getProtectedProperty($object, 'parentVar'));
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function get_protected_property_throws_exception_if_not_found(): void
    {
        $this->expectException(\ReflectionException::class);

        $object = new DummyClass();

        ClassAccessor::getProtectedProperty($object, 'inexistentVar');
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function set_protected_property(): void
    {
        $newValue = 'something new';
        $object = new DummyClass();
        self::assertNotSame($newValue, $object->getTestProperty());

        ClassAccessor::setProtectedProperty($object, 'testProperty', $newValue);
        self::assertSame($newValue, $object->getTestProperty());
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function set_protected_property_defined_in_parent_class(): void
    {
        $newValue = 'something new';
        $object = new DummyClass();
        self::assertNotSame($newValue, $object->getParentTestProperty());

        ClassAccessor::setProtectedProperty($object, 'parentTestProperty', $newValue);
        self::assertSame($newValue, $object->getParentTestProperty());
    }

    /**
     * @test
     */
    public function set_protected_property_fails_when_cant_find_the_property(): void
    {
        $this->expectException(ReflectionException::class);

        $object = new DummyClass();
        ClassAccessor::setProtectedProperty($object, 'i_dont_exist', 'non existent');
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public function instantiate_without_constructor_does_not_use_the_constructor(): void
    {
        $object = ClassAccessor::instantiateWithoutConstructor(DummyClass::class);
        self::assertNull($object->getAnotherVar());
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public static function invokeProtectedMethod_works_with_protected_methods(): void
    {
        $var = 100;
        $dummyObject = new DummyClass($var);

        self::assertEquals($var, ClassAccessor::invokeProtectedMethod($dummyObject, 'getVarProtected'));
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public static function invokeProtectedMethod_works_with_private_methods(): void
    {
        $var = 120;
        $dummyObject = new DummyClass($var);

        self::assertEquals($var, ClassAccessor::invokeProtectedMethod($dummyObject, 'getVarPrivate'));
    }
}
