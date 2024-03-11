<?php

namespace App\Note\Application\ViewModel;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use DateTimeImmutable;
use JsonSerializable;

class Note implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $content;
    private string $textColor;
    private string $backgroundColor;
    private DateTimeImmutable $createdAt;
    private bool $assignedToDashboard;
    private CatalogsIdsList $catalogsList;
    private SingleTasksIdsList $tasksList;
    private TasksGroupsIdsList $tasksGroupsList;

    public function __construct(
        int $id,
        string $name,
        string $content,
        string $textColor,
        string $backgroundColor,
        DateTimeImmutable $createdAt,
        bool $assignedToDashboard,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->textColor = $textColor;
        $this->backgroundColor = $backgroundColor;
        $this->createdAt = $createdAt;
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
            'content' => $this->content,
            'backgroundColor' => $this->backgroundColor,
            'textColor' => $this->textColor,
            'date' => $this->createdAt->format('Y-m-d H:i:s'),
            'assignedToDashboard' => $this->assignedToDashboard,
            'tasks' => array_merge($this->tasksList->getIds(), $this->tasksGroupsList->getIds()),
            'catalogs' => $this->catalogsList->getIds()
        ];
    }
}
