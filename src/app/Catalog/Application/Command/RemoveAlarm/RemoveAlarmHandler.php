<?php

namespace App\Catalog\Application\Command\RemoveAlarm;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\AlarmsGroupsAssigmentChanged;
use App\Catalog\Domain\Event\TasksAssigmentChanged;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\AlarmsTypesRepository;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TaskId;

class RemoveAlarmHandler extends ModifyCatalogHandler
{
    private AlarmsTypesRepository $alarmsTypesRepository;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        AlarmsTypesRepository $alarmsTypesRepository
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->alarmsTypesRepository = $alarmsTypesRepository;
    }

    public function handle(RemoveAlarm $command): void
    {
        $alarms = $this->alarmsTypesRepository->getAlarmsTypes($command->getAlarmId());
        $catalog = $this->getCatalog($command->getCatalogId());
        if (!$alarms->getAlarmsGroups()->isEmpty()) {
            if ($catalog->removeAlarmsGroup(new AlarmsGroupId($command->getAlarmId()))) {
                $this->eventEmitter->emit(
                    new AlarmsGroupsAssigmentChanged($catalog->getUserId(), new AlarmsGroupId($command->getAlarmId()))
                );
            }
        } elseif (!$alarms->getAlarms()->isEmpty()) {
            if ($catalog->removeAlarm(new AlarmId($command->getAlarmId()))) {
                $this->eventEmitter->emit(
                    new TasksAssigmentChanged($catalog->getUserId(), new TaskId($command->getAlarmId()))
                );
            }
        }
    }
}
