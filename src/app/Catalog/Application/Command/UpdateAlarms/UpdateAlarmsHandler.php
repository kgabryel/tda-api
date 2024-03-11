<?php

namespace App\Catalog\Application\Command\UpdateAlarms;

use App\Catalog\Application\Command\ModifyCatalogHandler;
use App\Catalog\Domain\Event\AlarmsGroupsAssigmentChanged;
use App\Catalog\Domain\Event\TasksAssigmentChanged;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\AlarmsTypesRepository;

class UpdateAlarmsHandler extends ModifyCatalogHandler
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

    public function handle(UpdateAlarms $command): void
    {
        $alarms = $this->alarmsTypesRepository->getAlarmsTypes(...$command->getAlarms());
        $catalog = $this->getCatalog($command->getCatalogId());
        if (!$alarms->getAlarmsGroups()->isEmpty()) {
            $changed = $catalog->updateAlarmsGroups(...$alarms->getAlarmsGroups()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new AlarmsGroupsAssigmentChanged($catalog->getUserId(), ...$changed->get()));
            }
        } elseif (!$alarms->getAlarms()->isEmpty()) {
            $changed = $catalog->updateAlarms(...$alarms->getAlarms()->toArray());
            if (!$changed->isEmpty()) {
                $this->eventEmitter->emit(new TasksAssigmentChanged($catalog->getUserId(), ...$changed->get()));
            }
        }
    }
}
