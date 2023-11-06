<?php

namespace App\File\Application\ViewModel;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use DateTime;
use DateTimeImmutable;
use JsonSerializable;

class File implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $originalName;
    private string $parsedSize;
    private string $extension;
    private DateTimeImmutable $createdAt;
    private DateTime $updatedAt;
    private bool $assignedToDashboard;
    private CatalogsIdsList $catalogsList;
    private SingleTasksIdsList $tasksList;
    private TasksGroupsIdsList $tasksGroupsList;

    public function __construct(
        int $id,
        string $name,
        string $originalName,
        string $parsedSize,
        string $extension,
        DateTimeImmutable $createdAt,
        DateTime $updatedAt,
        bool $assignedToDashboard,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->originalName = $originalName;
        $this->parsedSize = $parsedSize;
        $this->extension = $extension;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'originalName' => $this->originalName,
            'parsedSize' => $this->parsedSize,
            'extension' => $this->extension,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->createdAt->format('U') !== $this->updatedAt->format('U')
                ? $this->updatedAt->format('Y-m-d H:i:s') : null,
            'assignedToDashboard' => $this->assignedToDashboard,
            'tasks' => array_merge($this->tasksList->getIds(), $this->tasksGroupsList->getIds()),
            'catalogs' => $this->catalogsList->getIds()
        ];
    }
}
