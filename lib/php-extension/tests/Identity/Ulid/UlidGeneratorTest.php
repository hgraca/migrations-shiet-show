<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Identity\Ulid;

use Acme\PhpExtension\Identity\Ulid\UlidGenerator;
use Acme\PhpExtension\Test\AbstractTestCase;
use Symfony\Component\Uid\Ulid as SymfonyUlid;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class UlidGeneratorTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function generates_as_string(): void
    {
        self::assertTrue(
            SymfonyUlid::isValid(UlidGenerator::generate())
        );
    }

    /**
     * @test
     */
    public function override_default_generator_as_string(): void
    {
        $ulid = '01BX5ZZKBKACTAV9WEVGEMMVS0';
        UlidGenerator::overrideDefaultGenerator(
            function () use ($ulid) {
                return $ulid;
            }
        );

        self::assertEquals($ulid, UlidGenerator::generate());
    }

    /**
     * @test
     */
    public function reset(): void
    {
        $ulid = '01BX5ZZKBKACTAV9WEVGEMMVS0';
        UlidGenerator::overrideDefaultGenerator(
            function () use ($ulid) {
                return $ulid;
            }
        );

        self::assertEquals($ulid, UlidGenerator::generate());

        UlidGenerator::reset();
        self::assertNotEquals($ulid, UlidGenerator::generate());
    }
}
