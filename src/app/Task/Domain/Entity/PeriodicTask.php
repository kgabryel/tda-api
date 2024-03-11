<?php

namespace App\Task\Domain\Entity;

use App\Shared\Application\IntervalType;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\BookmarksListInterface;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\List\FilesListInterface;
use App\Shared\Domain\List\NotesListInterface;
use App\Shared\Domain\List\VideosListInterface;
use DateTimeImmutable;

class PeriodicTask extends Task
{
    private TasksGroupId $taskId;
    private ?AlarmsGroupId $alarmId;
    private TasksList $tasksList;
    private bool $active;
    private DateTimeImmutable $start;
    private ?DateTimeImmutable $stop;
    private int $interval;
    private IntervalType $intervalType;

    public function __construct(
        TasksGroupId $taskId,
        ?AlarmsGroupId $alarmId,
        TasksList $tasksList,
        bool $active,
        DateTimeImmutable $start,
        ?DateTimeImmutable $stop,
        int $interval,
        string $intervalType,
        UserId $userId,
        string $name,
        ?string $content,
        CatalogsListInterface $catalogsList,
        NotesListInterface $notesList,
        BookmarksListInterface $bookmarksList,
        FilesListInterface $filesList,
        VideosListInterface $videosList
    ) {
        parent::__construct(
            $userId,
            $name,
            $content,
            $catalogsList,
            $notesList,
            $bookmarksList,
            $filesList,
            $videosList
        );
        $this->taskId = $taskId;
        $this->alarmId = $alarmId;
        $this->tasksList = $tasksList;
        $this->active = $active;
        $this->start = $start;
        $this->stop = $stop;
        $this->interval = $interval;
        $this->intervalType = IntervalType::from($intervalType);
    }

    public function getTaskId(): TasksGroupId
    {
        return $this->taskId;
    }

    public function deleteTasks(): void
    {
        $this->tasksList->delete();
    }

    public function getTasksIds(): array
    {
        return $this->tasksList->getIds();
    }

    public function disconnectTasks(): void
    {
        $this->tasksList->disconnect();
    }

    public function hasAlarm(): bool
    {
        return $this->alarmId !== null;
    }

    public function getAlarmId(): ?AlarmsGroupId
    {
        return $this->alarmId;
    }

    public function updateName(string $name): bool
    {
        $result = parent::updateName($name);
        if (!$result) {
            return false;
        }
        $this->tasksList->updateName($name);

        return true;
    }

    public function updateContent(?string $content): bool
    {
        $result = parent::updateContent($content);
        if (!$result) {
            return false;
        }
        $this->tasksList->updateContent($content);

        return true;
    }

    public function deactivate(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "active" value, entity was deleted.');
        }
        if (!$this->active) {
            return false;
        }
        $this->active = false;

        return true;
    }

    public function activate(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "active" value, entity was deleted.');
        }
        if ($this->active) {
            return false;
        }
        $this->active = true;

        return true;
    }

    public function getTasksInFuture(): TasksInFuture
    {
        return $this->tasksList->getTasksInFuture();
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getStop(): ?DateTimeImmutable
    {
        return $this->stop;
    }

    public function getIntervalType(): IntervalType
    {
        return $this->intervalType;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
