<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Enum;

use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class AbstractEnumTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function enum(): void
    {
        $a = TestEnum::get(TestEnum::A);
        $b = TestEnum::get(TestEnum::B);

        self::assertSame(TestEnum::A, $a->getValue());
        self::assertSame(TestEnum::B, $b->getValue());
        self::assertSame(TestEnum::A, (string) $a);
        self::assertSame(TestEnum::B, (string) $b);
    }

    /**
     * @test
     */
    public function null(): void
    {
        $nullEnum = TestEnum::get(null);
        self::assertNull($nullEnum->getValue());
        self::assertSame('', (string) $nullEnum);
    }

    /**
     * @test
     */
    public function integer(): void
    {
        $integerEnum = TestEnum::get(3);
        self::assertSame(3, $integerEnum->getValue());
        self::assertSame('3', (string) $integerEnum);
    }

    /**
     * @test
     */
    public function is_should_return_true_when_comparing_objects_with_the_same_type_and_value(): void
    {
        $a = TestEnum::get(TestEnum::A);

        self::assertTrue($a->isA());
    }

    /**
     * @test
     */
    public function is_should_return_false_when_comparing_objects_with_the_same_type_and_different_value(): void
    {
        $a = TestEnum::get(TestEnum::A);

        self::assertFalse($a->isB());
    }

    /**
     * @test
     */
    public function equals_should_return_true_when_comparing_objects_with_the_same_type_and_value(): void
    {
        $a = TestEnum::get(TestEnum::A);

        self::assertTrue($a->equals(TestEnum::get(TestEnum::A)));
    }

    /**
     * @test
     */
    public function equals_should_return_false_when_comparing_objects_with_the_same_type_but_different_value(): void
    {
        $a = TestEnum::get(TestEnum::A);

        self::assertFalse($a->equals(TestEnum::get(TestEnum::B)));
    }

    /**
     * @test
     */
    public function equals_should_return_false_when_comparing_objects_with_the_same_value_but_different_type(): void
    {
        $a = TestEnum::get(TestEnum::A);

        self::assertFalse($a->equals(Test2Enum::get(Test2Enum::A)));
    }

    /**
     * @test
     */
    public function equals_should_return_true_when_comparing_objects_with_different_type_and_value(): void
    {
        $a = TestEnum::get(TestEnum::A);

        self::assertFalse($a->equals(Test2Enum::get(Test2Enum::C)));
    }

    /**
     * @test
     */
    public function exception_message(): void
    {
        $this->expectException(\InvalidArgumentException::class, 'Value [anything] is not matching any valid value of class "TestEnum". Valid values are [\'A\', \'BEE\', 1, 3, NULL, true].');

        TestEnum::get('anything');
    }

    /**
     * @test
     *
     * @dataProvider getInvertedCaseOptions
     */
    public function get_with_inverted_case_is_incorrect($option): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TestEnum::get($option);
    }

    public function getInvertedCaseOptions(): array
    {
        return [
            [mb_strtolower(TestEnum::A)],
            [mb_strtolower(TestEnum::B)],
        ];
    }

    /**
     * @test
     */
    public function get_with_strict_equal_match_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        TestEnum::get('1');
    }

    /**
     * @test
     */
    public function is_should_allow_to_call_methods_based_on_constant_names(): void
    {
        $enum = TestEnum::nameWithUnderscore();

        self::assertInstanceOf(TestEnum::class, $enum);
        self::assertSame(TestEnum::NAME_WITH_UNDERSCORE, $enum->getValue());
    }

    /**
     * @test
     */
    public function is_should_throw_an_exception_when_when_calling_an_invalid_method(): void
    {
        $this->expectException(\BadMethodCallException::class, 'App\\PhpExtension\\Test\\Enum\\TestEnum::isDoesNotExist() does not exist');

        TestEnum::isDoesNotExist();
    }

    /**
     * @test
     */
    public function get_valid_options(): void
    {
        self::assertSame(
            [
                TestEnum::A,
                TestEnum::B,
                TestEnum::A1,
                TestEnum::A3,
                TestEnum::ANULL,
                TestEnum::NAME_WITH_UNDERSCORE,
            ],
            TestEnum::getValidOptions()
        );
    }

    /**
     * @test
     */
    public function get_key(): void
    {
        $enum = TestEnum::b();

        self::assertSame('B', $enum->getKey());
    }
}
