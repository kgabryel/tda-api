<?php

namespace App\Video\Application\ViewModel;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use JsonSerializable;

class Video implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $ytId;
    private bool $isWatched;
    private bool $assignedToDashboard;
    private CatalogsIdsList $catalogsList;
    private SingleTasksIdsList $tasksList;
    private TasksGroupsIdsList $tasksGroupsList;

    public function __construct(
        int $id,
        string $name,
        string $ytId,
        bool $isWatched,
        bool $assignedToDashboard,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->ytId = $ytId;
        $this->isWatched = $isWatched;
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
            'ytId' => $this->ytId,
            'watched' => $this->isWatched,
            'assignedToDashboard' => $this->assignedToDashboard,
            'tasks' => array_merge($this->tasksList->getIds(), $this->tasksGroupsList->getIds()),
            'catalogs' => $this->catalogsList->getIds()
        ];
    }
}
