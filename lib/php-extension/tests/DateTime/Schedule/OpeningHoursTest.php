<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\DateTime\Schedule;

use Acme\PhpExtension\DateTime\DateTimeGenerator;
use Acme\PhpExtension\DateTime\Schedule\OpeningHours;
use Acme\PhpExtension\Test\AbstractTestCase;
use DateInterval;

/**
 * @group unit
 * @small
 *
 * @internal
 */
final class OpeningHoursTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function it_creates_a_schedule_and_converts_to_string(): void
    {
        $this->overrideDefaultDateTimeGenerator('2021-07-27');
        $schedule = (new OpeningHours())
            ->startsOn(DateTimeGenerator::generate()->sub(new DateInterval('P3D')))
            ->closedAllWeekDays()
            ->openOn(DateTimeGenerator::generate()->sub(new DateInterval('P2D')))
            ->closedOn(DateTimeGenerator::generate()->sub(new DateInterval('P1D')))
            ->endsOn(DateTimeGenerator::generate());

        $expectedSchedule = '{"start_date":"2021-07-24","end_date":"2021-07-27","open_date":["2021-07-25"],"closed_date":["2021-07-26"],"weekdays":[{"name":"monday","opens_at":"10:00","closes_at":"17:00","closed_week_day":true},{"name":"tuesday","opens_at":"10:00","closes_at":"17:00","closed_week_day":false},{"name":"wednesday","opens_at":"10:00","closes_at":"17:00","closed_week_day":false},{"name":"thursday","opens_at":"10:00","closes_at":"17:00","closed_week_day":false},{"name":"friday","opens_at":"10:00","closes_at":"17:00","closed_week_day":false},{"name":"saturday","opens_at":"10:00","closes_at":"17:00","closed_week_day":false},{"name":"sunday","opens_at":"10:00","closes_at":"17:00","closed_week_day":false}],"order_from":{"type":"months","value":""},"order_until":{"type":"hours","value":""}}';

        self::assertEquals($expectedSchedule, (string) $schedule);
    }
}
