<?php

namespace App\Bookmark\Application\Command\Create;

use App\Bookmark\Application\BookmarkManagerInterface;
use App\Bookmark\Application\FaviconServiceInterface;
use App\Bookmark\Domain\Event\Created;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Query\GetTaskTypes\GetTasksTypes;
use App\Shared\Domain\Entity\BookmarkId;

class CreateHandler extends AssignedUserCommandHandler
{
    private BookmarkManagerInterface $bookmarkManager;
    private FaviconServiceInterface $faviconService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        BookmarkManagerInterface $bookmarkManager,
        FaviconServiceInterface $faviconService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->bookmarkManager = $bookmarkManager;
        $this->faviconService = $faviconService;
    }

    public function handle(Create $command): BookmarkId
    {
        $tasks = $this->queryBus->handle(new GetTasksTypes(...$command->getTasksList()->getIds()));
        $bookmark = $this->bookmarkManager->create(
            $command->getName(),
            $command->getHref(),
            $this->faviconService->getAddress($command->getHref()),
            $command->isAssignedToDashboard(),
            $command->getTextColor(),
            $command->getBackgroundColor(),
            new CatalogsIdsList(...$command->getCatalogsList()->get()),
            new SingleTasksIdsList(...$tasks->getTasks()->toArray()),
            new TasksGroupsIdsList(...$tasks->getTasksGroups()->toArray()),
            $this->userId
        );
        $this->eventEmitter->emit(new Created($bookmark));

        return $bookmark->getBookmarkId();
    }
}
