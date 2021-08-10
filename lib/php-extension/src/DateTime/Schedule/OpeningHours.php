<?php

declare(strict_types=1);

namespace Acme\PhpExtension\DateTime\Schedule;

use Acme\PhpExtension\DateTime\DateTimeHelper;
use Acme\PhpExtension\Exception\OutOfBoundsException;
use Acme\PhpExtension\Helper\JsonHelper;
use DateTimeImmutable;

final class OpeningHours
{
    private const DEFAULT_OPEN_TIME = '10:00';
    private const DEFAULT_CLOSE_TIME = '17:00';

    /** @var array{start_date: DateTimeImmutable|null, end_date: DateTimeImmutable|null, open_date: array<empty, empty>|array<int, DateTimeImmutable>, closed_date: array<empty, empty>|array<int, DateTimeImmutable>, order_from: array{type: string, value: string}, order_until: array{type: string, value: string}, weekdays: array<empty, empty>|array{array{name: string, opens_at: string, closes_at: string, closed_week_day: bool}}} */
    private $calendar = [
        'start_date' => null,
        'end_date' => null,
        'open_date' => [],
        'closed_date' => [],
        'weekdays' => [],
        'order_from' => [
            'type' => 'months',
            'value' => '',
        ],
        'order_until' => [
            'type' => 'hours',
            'value' => '',
        ],
    ];

    public function __construct()
    {
        $weekDays = [];
        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $weekday) {
            $weekDays[] = [
                'name' => $weekday,
                'opens_at' => self::DEFAULT_OPEN_TIME,
                'closes_at' => self::DEFAULT_CLOSE_TIME,
                'closed_week_day' => false,
            ];
        }

        $this->calendar['weekdays'] = $weekDays;
    }

    public function startsOn(DateTimeImmutable $startDateTime): self
    {
        $this->calendar['start_date'] = $startDateTime;

        return $this;
    }

    public function endsOn(DateTimeImmutable $endDateTime): self
    {
        $this->calendar['end_date'] = $endDateTime;

        return $this;
    }

    public function openOn(DateTimeImmutable ...$openDateList): self
    {
        foreach ($openDateList as $openDate) {
            $this->calendar['open_date'][] = $openDate;
        }

        return $this;
    }

    public function closedOn(DateTimeImmutable ...$closedDateList): self
    {
        foreach ($closedDateList as $closedDate) {
            $this->calendar['closed_date'][] = $closedDate;
        }

        return $this;
    }

    public function closedAllWeekDays(): self
    {
        return $this->closedOnMonday()
            ->closedOnTuesday()
            ->closedOnWednesday()
            ->closedOnThursday()
            ->closedOnFriday()
            ->closedOnSaturday()
            ->closedOnSunday();
    }

    public function closedOnMonday(): self
    {
        return $this->closedOnWeekday('monday');
    }

    public function closedOnTuesday(): self
    {
        return $this->closedOnWeekday('tuesday');
    }

    public function closedOnWednesday(): self
    {
        return $this->closedOnWeekday('wednesday');
    }

    public function closedOnThursday(): self
    {
        return $this->closedOnWeekday('thursday');
    }

    public function closedOnFriday(): self
    {
        return $this->closedOnWeekday('friday');
    }

    public function closedOnSaturday(): self
    {
        return $this->closedOnWeekday('saturday');
    }

    public function closedOnSunday(): self
    {
        return $this->closedOnWeekday('sunday');
    }

    public function __toString()
    {
        $localCalendar = $this->calendar;
        $localCalendar['start_date'] = $localCalendar['start_date'] === null ? null : $localCalendar['start_date']->format(DateTimeHelper::DATE_FORMAT);
        $localCalendar['end_date'] = $localCalendar['end_date'] === null ? null : $localCalendar['end_date']->format(DateTimeHelper::DATE_FORMAT);

        $newOpenDateList = [];
        foreach ($localCalendar['open_date'] as $openDate) {
            $newOpenDateList[] = $openDate->format(DateTimeHelper::DATE_FORMAT);
        }
        $localCalendar['open_date'] = $newOpenDateList;

        $newClosedDateList = [];
        foreach ($localCalendar['closed_date'] as $closedDate) {
            $newClosedDateList[] = $closedDate->format(DateTimeHelper::DATE_FORMAT);
        }
        $localCalendar['closed_date'] = $newClosedDateList;

        return JsonHelper::encode($localCalendar);
    }

    private function closedOnWeekday(string $weekDay): self
    {
        foreach ($this->calendar['weekdays'] as $key => $weekdayArray) {
            if ($weekdayArray['name'] = $weekDay) {
                $this->calendar['weekdays'][$key]['closed_week_day'] = true;

                return $this;
            }
        }

        throw new OutOfBoundsException("The week day '$weekDay' does not exist.");
    }
}
