<?php

namespace App\Shared\Application;

use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Shared\Application\Dto\DatesList;
use App\Shared\CompareResult;
use App\Task\Domain\Entity\PeriodicTask;
use Carbon\Carbon;
use DateTimeImmutable;

class DateService
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $stop;
    private DateTimeImmutable $current;
    private int $interval;
    private IntervalType $intervalType;

    public function __construct(
        DateTimeImmutable $start,
        DateTimeImmutable $stop,
        DateTimeImmutable $minDate,
        ?DateTimeImmutable $maxDate,
        int $interval,
        IntervalType $intervalType
    ) {
        $this->interval = $interval;
        $this->intervalType = $intervalType;
        $this->stop = $stop;
        if ($maxDate !== null && $maxDate->getTimestamp() < $stop->getTimestamp()) {
            $this->stop = $maxDate;
        }
        $this->start = $start;
        while ($start->getTimestamp() < $minDate->getTimestamp()) {
            $this->start = $this->addInterval($this->start);
        }
        $this->current = $this->start;
    }

    private function addInterval(DateTimeImmutable $date): DateTimeImmutable
    {
        return match ($this->intervalType) {
            IntervalType::DAY => Carbon::instance($date)->addDays($this->interval)->toDateTimeImmutable(),
            IntervalType::WEEK => Carbon::instance($date)->addWeeks($this->interval)->toDateTimeImmutable(),
            IntervalType::MONTH => Carbon::instance($date)->addMonths($this->interval)->toDateTimeImmutable()
        };
    }

    public static function getDatesInRange(
        DateTimeImmutable $start,
        DateTimeImmutable $stop,
        int $interval,
        IntervalType $intervalType,
        ?DateTimeImmutable $minDate = null,
        ?DateTimeImmutable $maxDate = null
    ): DatesList {
        $dates = new DatesList();
        $dateService = new self($start, $stop, $minDate ?? $start, $maxDate ?? $stop, $interval, $intervalType);
        $currentDate = $dateService->getCurrent();
        while ($currentDate !== false) {
            $dates->addDate($currentDate);
            $dateService->setNext();
            $currentDate = $dateService->getCurrent();
        }

        return $dates;
    }

    public function getCurrent(): DateTimeImmutable|false
    {
        return $this->current->getTimestamp() > $this->stop->getTimestamp() ? false : $this->current;
    }

    public function setNext(): void
    {
        $this->current = $this->addInterval($this->current);
    }

    public static function getNextMonthEnd(): DateTimeImmutable
    {
        return Carbon::now()->addMonth()->endOfMonth()->toDateTimeImmutable();
    }

    public static function getNextMonthStart(): DateTimeImmutable
    {
        return Carbon::now()->addMonth()->startOfMonth()->toDateTimeImmutable();
    }

    public static function getNextNthMonthEnd(int $value): DateTimeImmutable
    {
        return Carbon::now()->addMonths($value)->endOfMonth()->toDateTimeImmutable();
    }

    public static function getNextNthMonthStart(int $value): DateTimeImmutable
    {
        return Carbon::now()->addMonths($value)->startOfMonth()->toDateTimeImmutable();
    }

    public static function get(
        DateTimeImmutable $start,
        DateTimeImmutable $stop,
        PeriodicAlarm|PeriodicTask $data
    ): self {
        return new self(
            $start,
            $stop,
            $data->getStart(),
            $data->getStop(),
            $data->getInterval(),
            $data->getIntervalType()
        );
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getStop(): DateTimeImmutable
    {
        return $this->stop;
    }

    public static function toStartOfDay(DateTimeImmutable|false $date): DateTimeImmutable|false
    {
        return $date === false ? false : $date->setTime(0, 0);
    }
}
