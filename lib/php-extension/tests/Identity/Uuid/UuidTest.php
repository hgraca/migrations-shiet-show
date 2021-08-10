<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Identity\Uuid;

use Acme\PhpExtension\Identity\Uuid\InvalidUuidStringException;
use Acme\PhpExtension\Identity\Uuid\Uuid;
use Acme\PhpExtension\Identity\Uuid\UuidGenerator;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class UuidTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function construct_throws_exception_if_invalid_uuid_string(): void
    {
        $this->expectException(InvalidUuidStringException::class);
        new Uuid('foo');
    }

    /**
     * @test
     */
    public function can_be_constructed_with_valid_uuid(): void
    {
        $uuidString = '7a980ca1-5504-4b8c-93be-605cb76700ec';
        $uuid = new Uuid($uuidString);

        self::assertEquals($uuidString, $uuid->toScalar());
    }

    /**
     * @test
     */
    public function can_be_constructed_without_argument_and_generates_a_uuid(): void
    {
        self::assertTrue(Uuid::isValid((new Uuid())->toScalar()));
    }

    /**
     * @test
     * @dataProvider provideUuid
     */
    public function is_valid(string $uuid, bool $expectedValue): void
    {
        self::assertEquals($expectedValue, Uuid::isValid($uuid));
    }

    public function provideUuid(): array
    {
        return [
            ['7a980ca1-5504-4b8c-93be-605cb76700ec', true],
            ['foo', false],
        ];
    }

    /**
     * @test
     */
    public function to_string_returns_correct_string(): void
    {
        $uuid = '7a980ca1-5504-4b8c-93be-605cb76700ec';

        self::assertEquals($uuid, (string) (new Uuid($uuid)));
    }

    /**
     * @test
     * @dataProvider provideUuidForComparison
     */
    public function knows_if_its_the_same_uuid(Uuid $uuid1, Uuid $uuid2, bool $expectedIsSame): void
    {
        self::assertEquals($expectedIsSame, $uuid1->equals($uuid2));
    }

    public function provideUuidForComparison(): array
    {
        $uuid = UuidGenerator::generate();

        return [
            [$uuid, $uuid, true],
            [$uuid, UuidGenerator::generate(), false],
        ];
    }
}
