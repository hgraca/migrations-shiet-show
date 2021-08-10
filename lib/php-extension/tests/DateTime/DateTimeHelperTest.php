<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\DateTime;

use Acme\PhpExtension\DateTime\DateTimeHelper;
use Acme\PhpExtension\Test\AbstractTestCase;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class DateTimeHelperTest extends AbstractTestCase
{
    /**
     * @test
     * @dataProvider provideStringDateTime
     */
    public function can_create_date_time_from_string(string $stringDateTime, string $format): void
    {
        $datetime = DateTimeHelper::fromStringToDateTimeImmutable($stringDateTime, $format);

        self::assertInstanceOf(DateTimeImmutable::class, $datetime);
        self::assertEquals($stringDateTime, $datetime->format($format));
    }

    public function provideStringDateTime(): array
    {
        return [
            ['2020-01-01', 'Y-m-d'],
            ['2020-01-01 19:21:32', 'Y-m-d H:i:s'],
            ['01-01-2020', 'd-m-Y'],
        ];
    }

    /**
     * @test
     * @dataProvider provideDateTime
     */
    public function cate_create_string_from_date_time(DateTimeInterface $dateTimeImmutable, string $format, string $stringDateTime): void
    {
        self::assertEquals($stringDateTime, DateTimeHelper::fromDateTimeToString($dateTimeImmutable, $format));
    }

    public function provideDateTime(): array
    {
        return [
            [DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-01', new DateTimeZone('Europe/Amsterdam')), 'Y-m-d', '2020-01-01'],
            [DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2018-10-21 19:21:32', new DateTimeZone('Europe/Amsterdam')), 'Y-m-d H:i:s', '2018-10-21 19:21:32'],
            [DateTimeImmutable::createFromFormat('d-m-Y', '21-10-2018', new DateTimeZone('Europe/Amsterdam')), 'd-m-Y', '21-10-2018'],
            [DateTime::createFromFormat('Y-m-d', '2020-01-01', new DateTimeZone('Europe/Amsterdam')), 'Y-m-d', '2020-01-01'],
            [DateTime::createFromFormat('Y-m-d H:i:s', '2018-10-21 19:21:32', new DateTimeZone('Europe/Amsterdam')), 'Y-m-d H:i:s', '2018-10-21 19:21:32'],
            [DateTime::createFromFormat('d-m-Y', '21-10-2018', new DateTimeZone('Europe/Amsterdam')), 'd-m-Y', '21-10-2018'],
        ];
    }

    /**
     * @test
     */
    public function groups_consecutive_dates_together(): void
    {
        $dateTimeImmutables = [
            DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-01'),
            DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-02'),
            DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-03'),
            DateTimeImmutable::createFromFormat('Y-m-d', '2021-01-01'),
            DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01'),
        ];

        $dateTimes = [
            DateTime::createFromFormat('Y-m-d', '2020-01-01'),
            DateTime::createFromFormat('Y-m-d', '2020-01-02'),
            DateTime::createFromFormat('Y-m-d', '2020-01-03'),
            DateTime::createFromFormat('Y-m-d', '2021-01-01'),
            DateTime::createFromFormat('Y-m-d', '2022-01-01'),
        ];

        $groupedDateTimeImmutables = DateTimeHelper::groupConsecutiveDatesTogether($dateTimeImmutables);
        $groupedDateTimes = DateTimeHelper::groupConsecutiveDatesTogether($dateTimes);

        self::assertCount(3, $groupedDateTimeImmutables);
        self::assertCount(3, $groupedDateTimes);

        self::assertEquals('2020-01-01', ($groupedDateTimeImmutables[0]['begin'])->format('Y-m-d'));
        self::assertEquals('2020-01-03', ($groupedDateTimeImmutables[0]['end'])->format('Y-m-d'));
        self::assertEquals('2021-01-01', ($groupedDateTimeImmutables[1]['begin'])->format('Y-m-d'));
        self::assertEquals('2021-01-01', ($groupedDateTimeImmutables[1]['end'])->format('Y-m-d'));
        self::assertEquals('2022-01-01', ($groupedDateTimeImmutables[2]['begin'])->format('Y-m-d'));
        self::assertEquals('2022-01-01', ($groupedDateTimeImmutables[2]['end'])->format('Y-m-d'));

        self::assertEquals('2020-01-01', ($groupedDateTimes[0]['begin'])->format('Y-m-d'));
        self::assertEquals('2020-01-03', ($groupedDateTimes[0]['end'])->format('Y-m-d'));
        self::assertEquals('2021-01-01', ($groupedDateTimes[1]['begin'])->format('Y-m-d'));
        self::assertEquals('2021-01-01', ($groupedDateTimes[1]['end'])->format('Y-m-d'));
        self::assertEquals('2022-01-01', ($groupedDateTimes[2]['begin'])->format('Y-m-d'));
        self::assertEquals('2022-01-01', ($groupedDateTimes[2]['end'])->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function returns_only_unique_dates(): void
    {
        $dateTimesWithDuplicates = [
            '2020-01-01 00:00:00',
            '2020-01-01 00:00:00',
            '2020-01-02 00:00:00',
            '2020-01-01 00:00:00',
        ];

        $uniqueDateTimes = DateTimeHelper::uniqueDates($dateTimesWithDuplicates);
        $stringifiedUniqueDateTimes = array_map(
            function (DatetimeImmutable $item) {
                return $item->format('Y-m-d H:i:s');
            },
            $uniqueDateTimes
        );

        self::assertCount(2, $uniqueDateTimes);
        self::assertContains('2020-01-01 00:00:00', $stringifiedUniqueDateTimes);
        self::assertContains('2020-01-02 00:00:00', $stringifiedUniqueDateTimes);
    }

    /**
     * @test
     */
    public function sorts_ascending_date_times(): void
    {
        $unsortedDateTimeImmutables = [
            new DateTimeImmutable('2021-10-01'),
            new DateTimeImmutable('2016-10-01'),
            new DateTimeImmutable('2012-10-01'),
            new DateTimeImmutable('2022-10-01'),
        ];

        $unsortedDateTimes = [
            new DateTime('2021-10-01'),
            new DateTime('2016-10-01'),
            new DateTime('2012-10-01'),
            new DateTime('2022-10-01'),
        ];

        $sortedDateTimeImmutables = DateTimeHelper::sortAscending(...$unsortedDateTimeImmutables);
        $sortedDateTimes = DateTimeHelper::sortAscending(...$unsortedDateTimes);

        self::assertEquals($unsortedDateTimeImmutables[0], $sortedDateTimeImmutables[2]);
        self::assertEquals($unsortedDateTimeImmutables[1], $sortedDateTimeImmutables[1]);
        self::assertEquals($unsortedDateTimeImmutables[2], $sortedDateTimeImmutables[0]);
        self::assertEquals($unsortedDateTimeImmutables[3], $sortedDateTimeImmutables[3]);

        self::assertEquals($unsortedDateTimes[0], $sortedDateTimes[2]);
        self::assertEquals($unsortedDateTimes[1], $sortedDateTimes[1]);
        self::assertEquals($unsortedDateTimes[2], $sortedDateTimes[0]);
        self::assertEquals($unsortedDateTimes[3], $sortedDateTimes[3]);
    }

    /**
     * @test
     */
    public function it_can_reset_seconds(): void
    {
        $original = new DateTimeImmutable('2021-10-01 00:00:12');

        $result = DateTimeHelper::resetSeconds($original);

        self::assertEquals('2021-10-01 00:00:00', $result->format(DateTimeHelper::MYSQL_DATE_TIME_FORMAT));
    }
}
