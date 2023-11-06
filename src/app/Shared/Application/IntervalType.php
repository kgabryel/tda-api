<?php

namespace App\Shared\Application;

enum IntervalType: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';

    public static function getValues(): array
    {
        return array_map(static fn(self $action) => $action->value, self::cases());
    }
}
