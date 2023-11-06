<?php

namespace App\Alarm\Application;

/**
 * informuje czy podczas usuwania powiadomienia zostal takze usuniety alarm.
 * Przy usuwaniu ostatniego powiadomienia usuwa tez alarm
 */
enum DeleteResult
{
    case DELETED;
    case NOT_DELETED;
}
