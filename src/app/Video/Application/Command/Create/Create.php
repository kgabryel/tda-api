<?php

namespace App\Video\Application\Command\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Video\Application\VideoInfo;

/**
 * Tworzy nowa film
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private bool $assignedToDashboard;
    private string|VideoInfo $href;
    private CatalogsIdsList $catalogsList;
    private TasksIdsList $tasksList;

    public function __construct(
        bool $assignedToDashboard,
        string|VideoInfo $href,
        CatalogsIdsList $catalogsList,
        TasksIdsList $tasksList
    ) {
        $this->assignedToDashboard = $assignedToDashboard;
        $this->href = $href;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }

    public function getHref(): string|VideoInfo
    {
        return $this->href;
    }

    public function getCatalogsList(): CatalogsIdsList
    {
        return $this->catalogsList;
    }

    public function getTasksList(): TasksIdsList
    {
        return $this->tasksList;
    }
}
