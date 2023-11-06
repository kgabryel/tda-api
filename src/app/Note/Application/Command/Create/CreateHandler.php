<?php

namespace App\Note\Application\Command\Create;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Note\Application\NoteManagerInterface;
use App\Note\Domain\Event\Created;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;
use App\Shared\Domain\Entity\NoteId;

class CreateHandler extends AssignedUserCommandHandler
{
    private NoteManagerInterface $noteManager;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        NoteManagerInterface $noteManager
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->eventEmitter = $eventEmitter;
        $this->noteManager = $noteManager;
        $this->queryBus = $queryBus;
    }

    public function handle(Create $command): NoteId
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasksList()->getIds()));
        $note = $this->noteManager->create(
            $command->getName(),
            $command->getContent(),
            $command->isAssignedToDashboard(),
            $command->getTextColor(),
            $command->getBackgroundText(),
            new CatalogsIdsList(...$command->getCatalogsList()->get()),
            new SingleTasksIdsList(...$tasks->getTasks()->toArray()),
            new TasksGroupsIdsList(...$tasks->getTasksGroups()->toArray()),
            $this->userId
        );
        $this->eventEmitter->emit(new Created($note));

        return $note->getNoteId();
    }
}
