<?php

namespace App\Task\Application\Command\PeriodicTask\Create;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\IntervalType;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Dto\BookmarksList;
use App\Task\Application\Dto\FilesList;
use App\Task\Application\Dto\NotesList;
use App\Task\Application\Dto\VideosList;
use DateTimeImmutable;

class TaskDto
{
    private TasksGroupId $taskId;
    private string $name;
    private ?string $content;
    private CatalogsIdsList $catalogsList;
    private FilesList $filesList;
    private NotesList $notesList;
    private VideosList $videosList;
    private BookmarksList $bookmarksList;
    private DateTimeImmutable $start;
    private ?DateTimeImmutable $stop;
    private int $interval;
    private IntervalType $intervalType;

    public function __construct(
        TasksGroupId $taskId,
        string $name,
        ?string $content,
        CatalogsIdsList $catalogsList,
        FilesList $filesList,
        NotesList $notesList,
        VideosList $videosList,
        BookmarksList $bookmarksList,
        DateTimeImmutable $start,
        ?DateTimeImmutable $stop,
        int $interval,
        IntervalType $intervalType
    ) {
        $this->taskId = $taskId;
        $this->name = $name;
        $this->content = $content;
        $this->catalogsList = $catalogsList;
        $this->filesList = $filesList;
        $this->notesList = $notesList;
        $this->videosList = $videosList;
        $this->bookmarksList = $bookmarksList;
        $this->start = $start;
        $this->stop = $stop;
        $this->interval = $interval;
        $this->intervalType = $intervalType;
    }

    public function getTaskId(): TasksGroupId
    {
        return $this->taskId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCatalogsList(): CatalogsIdsList
    {
        return $this->catalogsList;
    }

    public function getFilesList(): FilesList
    {
        return $this->filesList;
    }

    public function getNotesList(): NotesList
    {
        return $this->notesList;
    }

    public function getVideosList(): VideosList
    {
        return $this->videosList;
    }

    public function getBookmarksList(): BookmarksList
    {
        return $this->bookmarksList;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getStop(): ?DateTimeImmutable
    {
        return $this->stop;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function getIntervalType(): IntervalType
    {
        return $this->intervalType;
    }
}
