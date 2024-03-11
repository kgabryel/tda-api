<?php

namespace App\Alarm\Application;

use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use DateTimeImmutable;

interface AlarmManagerInterface
{
    public function deleteSingleAlarm(AlarmId $alarmId): void;

    public function updateSingleAlarm(SingleAlarm $alarm): void;

    public function deletePeriodicAlarm(AlarmsGroupId $alarmId): void;

    public function updatePeriodicAlarm(PeriodicAlarm $alarm): void;

    public function createSingleAlarm(AlarmDto $alarmDto, UserId $userId, string $deactivationCode): SingleAlarm;

    public function createPeriodicAlarm(
        AlarmsGroupId $id,
        string $name,
        ?string $content,
        CatalogsIdsList $catalogsList,
        UserId $userId,
        DateTimeImmutable $start,
        ?DateTimeImmutable $stop,
        int $interval,
        string $intervalType,
        ?TasksGroupId $tasksGroupId = null
    ): PeriodicAlarm;
}
