<?php

namespace App\Alarm\Application\Query\FindById;

use App\Alarm\Application\Query\AlarmQueryHandler;
use App\Alarm\Application\Query\AlarmType;
use App\Alarm\Application\ReadRepository;
use App\Alarm\Application\ViewModel\PeriodicAlarm as PeriodicAlarmView;
use App\Alarm\Application\ViewModel\SingleAlarm as SingleAlarmView;
use App\Alarm\Domain\Entity\PeriodicAlarm as PeriodicAlarmEntity;
use App\Alarm\Domain\Entity\SingleAlarm as SingleAlarmEntity;
use App\Alarm\Domain\WriteRepository;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;

class FindByIdQueryHandler extends AlarmQueryHandler
{
    private WriteRepository $writeRepository;

    public function __construct(ReadRepository $repository, WriteRepository $writeRepository)
    {
        parent::__construct($repository);
        $this->writeRepository = $writeRepository;
    }

    public function handle(FindById $query): SingleAlarmEntity|SingleAlarmView|PeriodicAlarmEntity|PeriodicAlarmView
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->findDomainModel($query);
        }

        return $this->findViewModel($query);
    }

    private function findDomainModel(FindById $query): SingleAlarmEntity|PeriodicAlarmEntity
    {
        return match ($query->getAlarmType()) {
            AlarmType::SINGLE_ALARM => $this->writeRepository->findSingleAlarmById(
                $this->userId,
                new AlarmId($query->getAlarmId())
            ),
            AlarmType::PERIODIC_ALARM => $this->writeRepository->findPeriodicAlarmById(
                $this->userId,
                new AlarmsGroupId($query->getAlarmId())
            ),
            null => $this->writeRepository->findById($this->userId, $query->getAlarmId())
        };
    }

    private function findViewModel(FindById $query): SingleAlarmView|PeriodicAlarmView
    {
        return match ($query->getAlarmType()) {
            AlarmType::SINGLE_ALARM => $this->readRepository->findSingleAlarmById(
                $this->userId,
                new AlarmId($query->getAlarmId())
            ),
            AlarmType::PERIODIC_ALARM => $this->readRepository->findPeriodicAlarmById(
                $this->userId,
                new AlarmsGroupId($query->getAlarmId())
            ),
            null => $this->readRepository->findById($this->userId, $query->getAlarmId())
        };
    }
}
