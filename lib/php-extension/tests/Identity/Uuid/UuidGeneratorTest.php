<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Identity\Uuid;

use Acme\PhpExtension\Identity\Uuid\Uuid;
use Acme\PhpExtension\Identity\Uuid\UuidGenerator;
use Acme\PhpExtension\Test\AbstractTestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class UuidGeneratorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function generate(): void
    {
        self::assertTrue(RamseyUuid::isValid((string) UuidGenerator::generate()));
    }

    /**
     * @test
     */
    public static function generateAsString(): void
    {
        self::assertTrue(RamseyUuid::isValid(UuidGenerator::generateAsString()));
    }

    /**
     * @test
     */
    public function override_default_generator_as_uuid(): void
    {
        $uuid = '7a980ca1-5504-4b8c-93be-605cb76700ec';
        UuidGenerator::overrideDefaultGenerator(
            function () use ($uuid) {
                return $uuid;
            }
        );

        self::assertEquals(new Uuid($uuid), UuidGenerator::generate());
    }

    /**
     * @test
     */
    public function override_default_generator_as_string(): void
    {
        $uuid = '7a980ca1-5504-4b8c-93be-605cb76700ec';
        UuidGenerator::overrideDefaultGenerator(
            function () use ($uuid) {
                return $uuid;
            }
        );

        self::assertEquals($uuid, UuidGenerator::generateAsString());
    }

    /**
     * @test
     */
    public function reset(): void
    {
        $uuid = '7a980ca1-5504-4b8c-93be-605cb76700ec';
        UuidGenerator::overrideDefaultGenerator(
            function () use ($uuid) {
                return $uuid;
            }
        );

        self::assertEquals($uuid, (string) UuidGenerator::generate());

        UuidGenerator::reset();
        self::assertNotEquals($uuid, (string) UuidGenerator::generate());
    }
}
