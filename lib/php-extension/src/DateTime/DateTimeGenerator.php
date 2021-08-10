<?php

declare(strict_types=1);

namespace Acme\PhpExtension\DateTime;

use Acme\PhpExtension\AbstractStaticClass;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

/**
 * The DateTimeGenerator is useful because it makes DateTime objects predictable and therefore testable.
 * Using DateTimeImmutable, provides for immutability, which helps reduce bugs.
 */
final class DateTimeGenerator extends AbstractStaticClass
{
    public const DATE_TIME_FORMAT = DateTimeHelper::MYSQL_DATE_TIME_FORMAT;
    public const UNIX_EPOCH_DATE_TIME = DateTimeHelper::UNIX_EPOCH_DATE_TIME;

    /** @var ?callable(?string, DateTimeZone): DateTimeImmutable */
    private static $customGenerator;

    /**
     * @throws DateTimeException
     */
    public static function generate(string $time = null, DateTimeZone $timezone = null): DateTimeImmutable
    {
        $timezone = $timezone ?: DateTimeHelper::getDefaultDateTimeZone();
        $customGenerator = self::$customGenerator;

        try {
            return $customGenerator
                ? $customGenerator($time, $timezone)
                : self::defaultGenerator($time, $timezone);
        } catch (Exception $e) {
            throw new DateTimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * @throws DateTimeException
     */
    public static function generateBeginningOfUnixEpoch(DateTimeZone $timezone = null): DateTimeImmutable
    {
        return self::generate(self::UNIX_EPOCH_DATE_TIME, $timezone);
    }

    /**
     * @param callable(?string, DateTimeZone): DateTimeImmutable $customGenerator
     */
    public static function overrideDefaultGenerator(callable $customGenerator): void
    {
        self::$customGenerator = $customGenerator;
    }

    public static function reset(): void
    {
        self::$customGenerator = null;
    }

    /**
     * @throws Exception
     */
    private static function defaultGenerator(?string $time = 'now', DateTimeZone $timezone = null): DateTimeImmutable
    {
        return new DateTimeImmutable($time ?? 'now', $timezone);
    }

    public static function generateAsString(
        string $format = self::DATE_TIME_FORMAT,
        string $time = 'now',
        DateTimeZone $timezone = null
    ): string {
        return self::generate($time, $timezone)->format($format);
    }
}
