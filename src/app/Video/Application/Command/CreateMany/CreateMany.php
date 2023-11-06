<?php

namespace App\Video\Application\Command\CreateMany;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;

/**
 * Tworzy kilka nowych filmow, na podstawie adresy playlisty
 */
#[CommandHandler(CreateManyHandler::class)]
class CreateMany implements Command
{
    private bool $assignedToDashboard;
    private string $href;
    private CatalogsIdsList $catalogsList;
    private TasksIdsList $tasksList;

    public function __construct(
        bool $assignedToDashboard,
        string $href,
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

    public function getHref(): string
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
