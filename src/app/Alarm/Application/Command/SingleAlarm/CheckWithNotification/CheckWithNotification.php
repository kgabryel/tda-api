<?php

namespace App\Alarm\Application\Command\SingleAlarm\CheckWithNotification;

use App\Alarm\Application\Command\SingleAlarm\Check\Check;
use App\Core\Cqrs\CommandHandler;

/**
 * Dezaktywuje alarm i emituje event, ze alarm zostal zmodyfikowany
 */
#[CommandHandler(CheckWithNotificationHandler::class)]
class CheckWithNotification extends Check
{
}
