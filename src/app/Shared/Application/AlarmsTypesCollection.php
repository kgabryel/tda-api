<?php

namespace App\Shared\Application;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use Ds\Set;

class AlarmsTypesCollection
{
    private Set $alarms;

    private Set $alarmsGroups;

    public function __construct()
    {
        $this->alarms = new Set();
        $this->alarmsGroups = new Set();
    }

    public function addAlarm(AlarmId $alarmId): void
    {
        $this->alarms->add($alarmId);
    }

    public function addAlarmsGroup(AlarmsGroupId $alarmsGroupId): void
    {
        $this->alarmsGroups->add($alarmsGroupId);
    }

    public function getAlarms(): Set
    {
        return $this->alarms;
    }

    public function getAlarmsGroups(): Set
    {
        return $this->alarmsGroups;
    }
}
