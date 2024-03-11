<?php

namespace App\Alarm\Infrastructure\Manager;

use App\Alarm\Application\AlarmManagerInterface;
use App\Alarm\Application\Command\SingleAlarm\Create\AlarmDto;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Infrastructure\Model\Alarm;
use App\Alarm\Infrastructure\Model\AlarmGroup;
use App\Core\Cache;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use DateTimeImmutable;

class AlarmManager implements AlarmManagerInterface
{
    public function deleteSingleAlarm(AlarmId $alarmId): void
    {
        $this->getSingleAlarm($alarmId)->delete();
        Cache::forget(self::getCacheKey($alarmId));
    }

    public function getSingleAlarm(AlarmId $alarmId): Alarm
    {
        $alarm = new Alarm();
        $alarm->id = $alarmId->getValue();
        $alarm->exists = true;

        return $alarm;
    }

    public static function getCacheKey(AlarmId|AlarmsGroupId|string $alarmId): string
    {
        return sprintf('alarms-%s', $alarmId);
    }

    public function updateSingleAlarm(SingleAlarm $alarm): void
    {
        $this->getSingleAlarm($alarm->getAlarmId())
            ->setName($alarm->getName())
            ->setContent($alarm->getContent())
            ->setTaskId($alarm->getTaskId())
            ->setChecked($alarm->isChecked())
            ->update();
    }

    public function deletePeriodicAlarm(AlarmsGroupId $alarmId): void
    {
        $this->getPeriodicAlarm($alarmId)->delete();
        Cache::forget(self::getCacheKey($alarmId));
    }

    public function getPeriodicAlarm(AlarmsGroupId $alarmId): AlarmGroup
    {
        $alarm = new AlarmGroup();
        $alarm->id = $alarmId->getValue();
        $alarm->exists = true;

        return $alarm;
    }

    public function createSingleAlarm(AlarmDto $alarmDto, UserId $userId, string $deactivationCode): SingleAlarm
    {
        $alarm = new Alarm();
        $alarm->setId($alarmDto->getAlarmId())
            ->setName($alarmDto->getName())
            ->setContent($alarmDto->getContent())
            ->setDeactivationCode($deactivationCode)
            ->setDate($alarmDto->getDate())
            ->setUserId($userId)
            ->setChecked(true);
        if ($alarmDto->getTaskId() !== null) {
            $alarm->setTaskId($alarmDto->getTaskId());
        }
        if ($alarmDto->getAlarmsGroupId() !== null) {
            $alarm->setAlarmsGroupId($alarmDto->getAlarmsGroupId());
        }
        $alarm->save();
        $alarm->catalogs()->attach($alarmDto->getCatalogsList()->getIds());

        $domainModel = $alarm->toDomainModel();
        $key = self::getCacheKey($domainModel->getAlarmId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

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
    ): PeriodicAlarm {
        $alarm = new AlarmGroup();
        $alarm->setId($id)
            ->setName($name)
            ->setContent($content)
            ->setUserId($userId)
            ->setStart($start)
            ->setStop($stop)
            ->setInterval($interval)
            ->setIntervalType($intervalType)
            ->setActiveValue($stop === null || $stop->getTimestamp() > time());
        if ($tasksGroupId !== null) {
            $alarm->setTaskGroup($tasksGroupId);
        }
        $alarm->save();
        $alarm->catalogs()->attach($catalogsList->getIds());
        $domainModel = $alarm->toDomainModel();
        $key = self::getCacheKey($domainModel->getAlarmId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

    public function updatePeriodicAlarm(PeriodicAlarm $alarm): void
    {
        $this->getPeriodicAlarm($alarm->getAlarmId())
            ->setName($alarm->getName())
            ->setContent($alarm->getContent())
            ->setActiveValue($alarm->isActive())
            ->update();
    }
}
