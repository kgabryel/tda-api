<?php

namespace App\Catalog\Application\Command\Create;

use App\Catalog\Application\CatalogManagerInterface;
use App\Catalog\Domain\Event\Created;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\AlarmsTypesRepository;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\Dto\AlarmsGroupsIdsList;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;
use App\Shared\Domain\Entity\CatalogId;

class CreateHandler extends AssignedUserCommandHandler
{
    private CatalogManagerInterface $catalogManager;
    private AlarmsTypesRepository $alarmsTypesRepository;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        CatalogManagerInterface $catalogManager,
        AlarmsTypesRepository $alarmsTypesRepository
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->catalogManager = $catalogManager;
        $this->alarmsTypesRepository = $alarmsTypesRepository;
    }

    public function handle(Create $command): CatalogId
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasks()->getIds()));

        $alarms = $this->alarmsTypesRepository->getAlarmsTypes(...$command->getAlarms()->getIds());

        $catalog = $this->catalogManager->create(
            $command->getName(),
            $command->isAssignedToDashboard(),
            new SingleTasksIdsList(...$tasks->getTasks()->toArray()),
            new TasksGroupsIdsList(...$tasks->getTasksGroups()->toArray()),
            new SingleAlarmsIdsList(...$alarms->getAlarms()->toArray()),
            new AlarmsGroupsIdsList(...$alarms->getAlarmsGroups()->toArray()),
            $command->getBookmarks(),
            $command->getNotes(),
            $command->getFiles(),
            $command->getVideos(),
            $this->userId
        );
        $this->eventEmitter->emit(new Created($catalog));

        return $catalog->getCatalogId();
    }
}
