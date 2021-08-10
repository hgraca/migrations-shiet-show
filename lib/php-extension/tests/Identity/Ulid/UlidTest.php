<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Identity\Ulid;

use Acme\PhpExtension\Identity\Ulid\AbstractUlid;
use Acme\PhpExtension\Identity\Ulid\InvalidUlidStringException;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class UlidTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function cannot_construct_with_invalid_ulid(): void
    {
        $this->expectException(InvalidUlidStringException::class);
        new DummyUlid('invalid-ulid');
    }

    /**
     * @test
     */
    public function can_be_constructed_with_valid_ulid(): void
    {
        $ulidString = '01BX5ZZKBKACTAV9WEVGEMMVRZ';
        $ulid = new DummyUlid($ulidString);

        self::assertSame($ulidString, $ulid->toScalar());
    }

    /**
     * @test
     */
    public function can_be_constructed_without_argument_and_generate_a_valid_ulid(): void
    {
        self::assertTrue(
            AbstractUlid::isValid((new DummyUlid())->toScalar())
        );
    }

    /**
     * @test
     * @dataProvider provideUlid
     */
    public function can_validate_ulids(string $ulid, bool $expectedValue): void
    {
        self::assertEquals($expectedValue, AbstractUlid::isValid($ulid));
    }

    public function provideUlid(): array
    {
        return [
            ['01BX5ZZKBKACTAV9WEVGEMMVRZ', true],
            ['foo', false],
        ];
    }

    /**
     * @test
     * @dataProvider provideUlidForComparison
     */
    public function knows_if_its_the_same_ulid(AbstractUlid $ulid1, AbstractUlid $ulid2, bool $expectedIsSame): void
    {
        self::assertEquals($expectedIsSame, $ulid1->equals($ulid2));
    }

    public function provideUlidForComparison(): array
    {
        $ulid = new DummyUlid();

        return [
            [$ulid, $ulid, true],
            [$ulid, new DummyUlid(), false],
        ];
    }
}
