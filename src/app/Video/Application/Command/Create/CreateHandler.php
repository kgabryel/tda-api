<?php

namespace App\Video\Application\Command\Create;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\VideoManagerInterface;
use App\Video\Application\YtServiceInterface;
use App\Video\Domain\Event\Created;

class CreateHandler extends AssignedUserCommandHandler
{
    private VideoManagerInterface $videoManager;
    private YtServiceInterface $ytService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        VideoManagerInterface $videoManager,
        YtServiceInterface $ytService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->videoManager = $videoManager;
        $this->ytService = $ytService;
    }

    public function handle(Create $command): VideoId
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasksList()->getIds()));
        $video = $this->videoManager->create(
            is_string($command->getHref()) ? $this->ytService->getVideoInfo($command->getHref())
                : $command->getHref(),
            $command->isAssignedToDashboard(),
            new CatalogsIdsList(...$command->getCatalogsList()->get()),
            new SingleTasksIdsList(...$tasks->getTasks()->toArray()),
            new TasksGroupsIdsList(...$tasks->getTasksGroups()->toArray()),
            $this->userId
        );
        $this->eventEmitter->emit(new Created($video));

        return $video->getVideoId();
    }
}
