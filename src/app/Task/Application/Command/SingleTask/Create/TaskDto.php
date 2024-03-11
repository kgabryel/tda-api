<?php

namespace App\Task\Application\Command\SingleTask\Create;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Dto\BookmarksList;
use App\Task\Application\Dto\FilesList;
use App\Task\Application\Dto\NotesList;
use App\Task\Application\Dto\VideosList;
use App\Task\Domain\Entity\PeriodicTask;
use DateTimeImmutable;

class TaskDto
{
    private TaskId $taskId;
    private string $name;
    private ?string $content;
    private ?DateTimeImmutable $date;
    private ?TaskId $mainTask;
    private CatalogsIdsList $catalogsList;
    private NotesList $notesList;
    private FilesList $filesList;
    private VideosList $videosList;
    private BookmarksList $bookmarksList;
    private ?TasksGroupId $tasksGroupId;

    public function __construct(
        TaskId $taskId,
        string $name,
        ?string $content,
        ?DateTimeImmutable $date,
        ?TaskId $mainTask,
        CatalogsIdsList $catalogsList,
        NotesList $notesList,
        FilesList $filesList,
        VideosList $videosList,
        BookmarksList $bookmarksList
    ) {
        $this->taskId = $taskId;
        $this->name = $name;
        $this->content = $content;
        $this->date = $date;
        $this->mainTask = $mainTask;
        $this->catalogsList = $catalogsList;
        $this->notesList = $notesList;
        $this->filesList = $filesList;
        $this->videosList = $videosList;
        $this->bookmarksList = $bookmarksList;
        $this->tasksGroupId = null;
    }

    public static function fromPeriodicTask(PeriodicTask $task, TaskId $id, ?DateTimeImmutable $date): self
    {
        $dto = new self(
            $id,
            $task->getName(),
            $task->getContent(),
            $date,
            null,
            new CatalogsIdsList(),
            new NotesList(),
            new FilesList(),
            new VideosList(),
            new BookmarksList()
        );
        $dto->setTasksGroupId($task->getTaskId());

        return $dto;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public static function get(string $name, ?string $content, TaskId $id, ?DateTimeImmutable $date): self
    {
        return new self(
            $id,
            $name,
            $content,
            $date,
            null,
            new CatalogsIdsList(),
            new NotesList(),
            new FilesList(),
            new VideosList(),
            new BookmarksList()
        );
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function getMainTask(): ?TaskId
    {
        return $this->mainTask;
    }

    public function getCatalogsList(): CatalogsIdsList
    {
        return $this->catalogsList;
    }

    public function getNotesList(): NotesList
    {
        return $this->notesList;
    }

    public function getFilesList(): FilesList
    {
        return $this->filesList;
    }

    public function getVideosList(): VideosList
    {
        return $this->videosList;
    }

    public function getBookmarksList(): BookmarksList
    {
        return $this->bookmarksList;
    }

    public function getTasksGroupId(): ?TasksGroupId
    {
        return $this->tasksGroupId;
    }

    public function setTasksGroupId(TasksGroupId $tasksGroupId): void
    {
        $this->tasksGroupId = $tasksGroupId;
    }
}
