<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test;

use Acme\PhpExtension\DateTime\DateTimeGenerator;
use Acme\PhpExtension\DateTime\DateTimeHelper;
use Acme\PhpExtension\Identity\Uuid\UuidGenerator;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * A unit test will test a method, class or set of classes in isolation from the tools and delivery mechanisms.
 * How isolated the test needs to be, it depends on the situation and how you decide to test the application.
 * However, it is important to note that these tests do not need to boot the application.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @after
     */
    public function resetDateTimeGenerator(): void
    {
        DateTimeGenerator::reset();
    }

    /**
     * @after
     */
    public function resetUuidGenerator(): void
    {
        UuidGenerator::reset();
    }

    protected function overrideDefaultDateTimeGenerator(string $overrideDateTime): void
    {
        DateTimeGenerator::overrideDefaultGenerator(
            function (?string $givenDateTime = null, ?DateTimeZone $dateTimeZone = null) use ($overrideDateTime): DateTimeImmutable {
                return new DateTimeImmutable(
                    $givenDateTime ?? $overrideDateTime,
                    $dateTimeZone ?? DateTimeHelper::getDefaultDateTimeZone()
                );
            }
        );
    }
}
