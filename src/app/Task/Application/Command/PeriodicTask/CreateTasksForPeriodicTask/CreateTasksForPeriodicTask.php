<?php

namespace App\Task\Application\Command\PeriodicTask\CreateTasksForPeriodicTask;

use App\Alarm\Application\Command\PeriodicAlarm\Create\TasksGroupsList;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\DatesList;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;

/**
 * Tworzy zadania pojedyncze dla nowo utworzonego zadania okresowego
 */
#[CommandHandler(CreateTasksForPeriodicTaskHandler::class)]
class CreateTasksForPeriodicTask implements Command
{
    private DatesList $dates;
    private UserId $userId;
    private TasksGroupId $taskId;
    private string $name;
    private ?string $content;
    private TasksGroupsList $groups;

    public function __construct(
        DatesList $dates,
        UserId $userId,
        TasksGroupId $taskId,
        string $name,
        ?string $content,
        TasksGroupsList $groups
    ) {
        $this->dates = $dates;
        $this->userId = $userId;
        $this->taskId = $taskId;
        $this->name = $name;
        $this->content = $content;
        $this->groups = $groups;
    }

    public function getTaskId(): TasksGroupId
    {
        return $this->taskId;
    }

    public function getDates(): DatesList
    {
        return $this->dates;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getGroups(): TasksGroupsList
    {
        return $this->groups;
    }
}
