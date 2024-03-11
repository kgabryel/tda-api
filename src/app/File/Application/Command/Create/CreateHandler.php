<?php

namespace App\File\Application\Command\Create;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\File\Application\FileManagerInterface;
use App\File\Domain\Entity\File;
use App\File\Domain\Event\Created;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\FileId;

class CreateHandler extends AssignedUserCommandHandler
{
    private FileManagerInterface $fileManager;
    private UuidInterface $uuid;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        FileManagerInterface $fileManager,
        UuidInterface $uuid
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->fileManager = $fileManager;
        $this->uuid = $uuid;
    }

    public function handle(Create $command): FileId
    {
        $path = $this->uuid->getValue();
        $command->getFile()->storeAs(File::STORAGE_DIRECTORY, $path);
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasksList()->getIds()));

        $file = $this->fileManager->create(
            $command->getName(),
            $command->isAssignedToDashboard(),
            $path,
            $command->getFile(),
            new CatalogsIdsList(...$command->getCatalogsList()->get()),
            new SingleTasksIdsList(...$tasks->getTasks()->toArray()),
            new TasksGroupsIdsList(...$tasks->getTasksGroups()->toArray()),
            $this->userId
        );
        $this->eventEmitter->emit(new Created($file));

        return $file->getFileId();
    }
}
