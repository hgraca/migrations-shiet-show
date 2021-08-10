<?php

declare(strict_types=1);

namespace Acme\PhpExtension\DateTime;

use Acme\PhpExtension\AbstractStaticClass;
use Acme\PhpExtension\DateTime\Exception\DateTimeConversionException;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use function get_class;

final class DateTimeHelper extends AbstractStaticClass
{
    public const DEFAULT_TIMEZONE = 'Europe/Amsterdam';
    public const MYSQL_DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    public const DATE_FORMAT = 'Y-m-d';
    public const UNIX_EPOCH_DATE_TIME = '1970-01-01 00:00:00';

    public static function fromDateTimeToString(
        DateTimeInterface $dateTime,
        ?string $format = DateTimeInterface::ATOM
    ): string {
        $format = $format ?: DateTimeInterface::ATOM;
        if ($dateTime instanceof DateTime || $dateTime instanceof DateTimeImmutable) {
            return $dateTime->setTimezone(self::getDefaultDateTimeZone())
                ->format($format);
        }

        $dateTimeString = $dateTime->format(DateTimeInterface::ATOM);
        $timeZone = $dateTime->getTimezone()->getName();
        $class = get_class($dateTime);

        throw new DateTimeConversionException(
            "Failed to convert a DateTime representing '${dateTimeString}' in TimeZone ${$timeZone},"
            . " to string using the format '${format}' because the given value is of type ${class} "
            . 'but it needs to be either DateTime or DateTimeImmutable.'
        );
    }

    public static function fromStringToDateTimeImmutable(
        string $dateTimeString,
        ?string $format = DateTimeInterface::ATOM
    ): DateTimeImmutable {
        $format = $format ?: DateTimeInterface::ATOM;
        $dateTime = DateTimeImmutable::createFromFormat(
            $format,
            $dateTimeString,
            self::getDefaultDateTimeZone()
        );

        if ($dateTime === false) {
            throw new DateTimeConversionException(
                "Failed to convert '${dateTimeString}' to DateTime using the format '${format}'."
            );
        }

        return $dateTime;
    }

    public static function getDefaultDateTimeZone(): DateTimeZone
    {
        static $dateTimeZone;

        if (!$dateTimeZone instanceof DateTimeZone) {
            $dateTimeZone = new DateTimeZone(self::DEFAULT_TIMEZONE);
        }

        return $dateTimeZone;
    }

    /**
     * @param list<DateTimeInterface> $availableDates
     *
     * @return list<DateTimeInterface[]>
     */
    public static function groupConsecutiveDatesTogether(array $availableDates): array
    {
        $dateRanges = [];
        $rangeCount = 0;
        $previousDate = null;
        $availableDate = null;

        if (count($availableDates) === 0) {
            return $dateRanges;
        }

        $sortedAvailableDates = self::sortAscending(...$availableDates);

        /** @var DateTimeInterface[] $sortedAvailableDates */
        foreach ($sortedAvailableDates as $availableDate) {
            if ($previousDate === null) {
                $dateRanges[$rangeCount]['begin'] = $previousDate = $availableDate;
                continue;
            }

            if (
                $previousDate->format('Y-m-d') === date('Y-m-d', strtotime('-1 day', $availableDate->getTimestamp()))
            ) {
                $previousDate = $availableDate;
                continue;
            }

            $dateRanges[$rangeCount]['end'] = $previousDate;
            ++$rangeCount;
            $dateRanges[$rangeCount]['begin'] = $previousDate = $availableDate;
        }

        $dateRanges[$rangeCount]['end'] = $availableDate;

        /** @var list<array{begin: DateTimeImmutable, end: DateTimeImmutable}> */
        return $dateRanges;
    }

    /**
     * @param list<string> $voucherAvailableOnDates
     *
     * @return array<int, DateTimeImmutable|false>
     */
    public static function uniqueDates(array $voucherAvailableOnDates): array
    {
        return array_unique(
            array_map(
                function ($date) {
                    return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);
                },
                $voucherAvailableOnDates
            ),
            SORT_REGULAR
        );
    }

    public static function sortAscending(DateTimeInterface ...$datetimeList): array
    {
        usort($datetimeList, function (DateTimeInterface $a, DateTimeInterface $b) {
            return $a <=> $b;
        });

        return $datetimeList;
    }

    public static function resetSeconds(DateTimeImmutable $datetime): DateTimeImmutable
    {
        return $datetime->setTime((int) $datetime->format('H'), (int) $datetime->format('i'));
    }
}
