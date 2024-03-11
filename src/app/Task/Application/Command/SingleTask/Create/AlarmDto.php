<?php

namespace App\Task\Application\Command\SingleTask\Create;

use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto as Alarm;
use App\Alarm\Application\Command\SingleAlarm\Create\Notifications;

//do zmiany, powinno korzystac z portow, nie importowac klasy z modulu alarmu
class AlarmDto
{
    private Alarm $alarm;
    private Notifications $notifications;

    public function __construct(Alarm $alarm, Notifications $notifications)
    {
        $this->alarm = $alarm;
        $this->notifications = $notifications;
    }

    public function getAlarm(): Alarm
    {
        return $this->alarm;
    }

    public function getNotifications(): Notifications
    {
        return $this->notifications;
    }
}
