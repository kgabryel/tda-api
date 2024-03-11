<?php

namespace App\File\Application\Command\Create;

use App\Core\Cqrs\CommandHandler;
use App\File\Application\UploadedFileInterface;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;

/**
 * Tworzy nowy plik
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private string $name;
    private bool $assignedToDashboard;
    private UploadedFileInterface $file;
    private CatalogsIdsList $catalogsList;
    private TasksIdsList $tasksList;

    public function __construct(
        string $name,
        bool $assignedToDashboard,
        UploadedFileInterface $file,
        CatalogsIdsList $catalogsList,
        TasksIdsList $tasksList
    ) {
        $this->name = $name;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->file = $file;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }

    public function getFile(): UploadedFileInterface
    {
        return $this->file;
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
