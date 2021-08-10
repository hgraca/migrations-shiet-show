<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\DateTime;

use Acme\PhpExtension\DateTime\TimeInterval;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @group unit
 * @small
 *
 * @internal
 */
final class TimeIntervalTest extends AbstractTestCase
{
    private const MILISECONDS_IN_SECOND = 1000;

    private const SECONDS_IN_MINUTE = 60;
    private const MILISECONDS_IN_MINUTE = 60000;

    private const MINUTES_IN_HOUR = 60;
    private const SECONDS_IN_HOUR = 3600;
    private const MILISECONDS_IN_HOUR = 3600000;

    private const HOURS_IN_DAY = 24;
    private const MINUTES_IN_DAY = 1440;
    private const SECONDS_IN_DAY = 86400;
    private const MILISECONDS_IN_DAY = 86400000;

    /**
     * @test
     */
    public function instantiates_from_seconds(): void
    {
        $interval = TimeInterval::fromSeconds(1);

        self::assertSame(1, $interval->getSeconds());
        self::assertSame(self::MILISECONDS_IN_SECOND, $interval->getMiliseconds());
    }

    /**
     * @test
     */
    public function instantiates_from_minutes(): void
    {
        $interval = TimeInterval::fromMinutes(1);

        self::assertSame(1, $interval->getMinutes());
        self::assertSame(self::SECONDS_IN_MINUTE, $interval->getSeconds());
        self::assertSame(self::MILISECONDS_IN_MINUTE, $interval->getMiliseconds());
    }

    /**
     * @test
     */
    public function instantiates_from_hours(): void
    {
        $interval = TimeInterval::fromHours(1);

        self::assertSame(1, $interval->getHours());
        self::assertSame(self::MINUTES_IN_HOUR, $interval->getMinutes());
        self::assertSame(self::SECONDS_IN_HOUR, $interval->getSeconds());
        self::assertSame(self::MILISECONDS_IN_HOUR, $interval->getMiliseconds());
    }

    /**
     * @test
     */
    public function instantiates_from_days(): void
    {
        $interval = TimeInterval::fromDays(1);

        self::assertSame(1, $interval->getDays());
        self::assertSame(self::HOURS_IN_DAY, $interval->getHours());
        self::assertSame(self::MINUTES_IN_DAY, $interval->getMinutes());
        self::assertSame(self::SECONDS_IN_DAY, $interval->getSeconds());
        self::assertSame(self::MILISECONDS_IN_DAY, $interval->getMiliseconds());
    }

    /**
     * @test
     * @dataProvider datetimeProvider
     */
    public function instantiates_from_datetime(string $datetime, int $expectedMiliseconds, int $expectedSeconds): void
    {
        $interval = TimeInterval::until(
            new \DateTimeImmutable($datetime)
        );

        self::assertSame($expectedSeconds, $interval->getSeconds());
        self::assertSame($expectedMiliseconds, $interval->getMiliseconds());
    }

    public function datetimeProvider(): array
    {
        return [
            ['+1 minute', self::MILISECONDS_IN_MINUTE, self::SECONDS_IN_MINUTE],
            ['+1 hour', self::MILISECONDS_IN_HOUR, self::SECONDS_IN_HOUR],
            ['+1 day', self::MILISECONDS_IN_DAY, self::SECONDS_IN_DAY],
        ];
    }
}
