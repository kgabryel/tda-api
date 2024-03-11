<?php

namespace App\Bookmark\Application\ViewModel;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use JsonSerializable;

class Bookmark implements JsonSerializable
{
    private int $id;
    private string $name;
    private bool $assignedToDashboard;
    private string $href;
    private ?string $icon;
    private string $backgroundColor;
    private string $textColor;
    private CatalogsIdsList $catalogsList;
    private SingleTasksIdsList $tasksList;
    private TasksGroupsIdsList $tasksGroupsList;

    public function __construct(
        int $id,
        string $name,
        bool $assignedToDashboard,
        string $href,
        ?string $icon,
        string $backgroundColor,
        string $textColor,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->href = $href;
        $this->icon = $icon;
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
        $this->catalogsList = $catalogsList;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'href' => $this->href,
            'icon' => $this->icon,
            'backgroundColor' => $this->backgroundColor,
            'textColor' => $this->textColor,
            'assignedToDashboard' => $this->assignedToDashboard,
            'tasks' => array_merge($this->tasksList->getIds(), $this->tasksGroupsList->getIds()),
            'catalogs' => $this->catalogsList->getIds()
        ];
    }
}
