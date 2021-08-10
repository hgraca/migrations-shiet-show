<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\DateTime;

use Acme\PhpExtension\DateTime\DateTimeGenerator;
use Acme\PhpExtension\DateTime\DateTimeHelper;
use Acme\PhpExtension\Test\AbstractTestCase;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use function time;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class DateTimeGeneratorTest extends AbstractTestCase
{
    private const TOLERATED_SECONDS_DIFF = 5;
    private const DATE_TIME_ZONE = 'Europe/Amsterdam';

    /**
     * @test
     */
    public function generate_now(): void
    {
        self::assertEqualsWithDelta(
            time(),
            DateTimeGenerator::generate()->getTimestamp(),
            self::TOLERATED_SECONDS_DIFF
        );
    }

    /**
     * @test
     * @dataProvider provideDateTime
     */
    public function generate(
        string $time,
        ?DateTimeZone $timezone,
        int $expectedTimestamp
    ): void {
        self::assertEquals($expectedTimestamp, DateTimeGenerator::generate($time, $timezone)->getTimestamp());
    }

    public function provideDateTime(): array
    {
        return [
            ['Sun, 22 Apr 2018 19:21:32 GMT', null, 1524424892],
            ['Sunday, 22 April 2018 19:21:32', new DateTimeZone('Europe/Amsterdam'), 1524417692],
        ];
    }

    /**
     * @test
     */
    public function generates_beginning_of_unix_epoch(): void
    {
        self::assertEqualsWithDelta(
            new DateTimeImmutable(DateTimeGenerator::UNIX_EPOCH_DATE_TIME, DateTimeHelper::getDefaultDateTimeZone()),
            DateTimeGenerator::generateBeginningOfUnixEpoch(),
            self::TOLERATED_SECONDS_DIFF
        );
    }

    /**
     * @test
     * @dataProvider provideDateTimeStrings
     */
    public function generate_as_string(
        string $dateTime,
        ?string $format,
        string $expected
    ): void {
        DateTimeGenerator::overrideDefaultGenerator(
            function () use ($dateTime) {
                return new DateTimeImmutable($dateTime, new DateTimeZone(self::DATE_TIME_ZONE));
            }
        );

        if ($format === null) {
            self::assertEquals(
                $expected,
                DateTimeGenerator::generateAsString(
                    DateTimeGenerator::DATE_TIME_FORMAT,
                    'now',
                    new DateTimeZone(self::DATE_TIME_ZONE)
                )
            );
        } else {
            self::assertEquals($expected, DateTimeGenerator::generateAsString($format));
        }
    }

    public function provideDateTimeStrings(): array
    {
        return [
            ['2018-10-21 19:21:32', null, '2018-10-21 19:21:32'],
            ['2018-10-21 19:21:32', DateTimeHelper::MYSQL_DATE_TIME_FORMAT, '2018-10-21 19:21:32'],
            ['2018-10-21 19:21:32', DateTimeInterface::ATOM, '2018-10-21T19:21:32+02:00'],
        ];
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function override_default_generator(): void
    {
        $date = '2018-10-21';
        DateTimeGenerator::overrideDefaultGenerator(
            function () use ($date) {
                return new DateTimeImmutable($date);
            }
        );

        self::assertEquals(new DateTimeImmutable($date), DateTimeGenerator::generate('abc'));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function reset(): void
    {
        $date = '2018-10-21';
        DateTimeGenerator::overrideDefaultGenerator(
            function () use ($date) {
                return new DateTimeImmutable($date);
            }
        );
        self::assertEquals(new DateTimeImmutable($date), DateTimeGenerator::generate('abc'));

        DateTimeGenerator::reset();
        self::assertLessThan(
            time() + self::TOLERATED_SECONDS_DIFF,
            DateTimeGenerator::generate()->getTimestamp()
        );
    }
}
