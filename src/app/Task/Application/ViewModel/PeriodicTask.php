<?php

namespace App\Task\Application\ViewModel;

use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\VideosIdsList;
use DateTimeImmutable;
use JsonSerializable;

class PeriodicTask implements JsonSerializable
{
    private string $id;
    private string $name;
    private ?string $content;
    private int $interval;
    private string $intervalType;
    private DateTimeImmutable $start;
    private ?DateTimeImmutable $stop;
    private ?string $alarmId;
    private bool $isActive;
    private CatalogsIdsList $catalogs;
    private SingleTasksIdsList $tasksList;
    private NotesIdsList $notesList;
    private BookmarksIdsList $bookmarksList;
    private FilesIdsList $filesList;
    private VideosIdsList $videosList;
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $id,
        string $name,
        ?string $content,
        int $interval,
        string $intervalType,
        DateTimeImmutable $start,
        ?DateTimeImmutable $stop,
        ?string $alarmId,
        bool $isActive,
        CatalogsIdsList $catalogs,
        SingleTasksIdsList $tasksList,
        NotesIdsList $notesList,
        BookmarksIdsList $bookmarksList,
        FilesIdsList $filesList,
        VideosIdsList $videosList,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->interval = $interval;
        $this->intervalType = $intervalType;
        $this->start = $start;
        $this->stop = $stop;
        $this->alarmId = $alarmId;
        $this->isActive = $isActive;
        $this->catalogs = $catalogs;
        $this->tasksList = $tasksList;
        $this->notesList = $notesList;
        $this->bookmarksList = $bookmarksList;
        $this->filesList = $filesList;
        $this->videosList = $videosList;
        $this->createdAt = $createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'periodic' => true,
            'interval' => $this->interval,
            'intervalType' => $this->intervalType,
            'start' => $this->start->format('Y-m-d'),
            'stop' => $this->stop?->format('Y-m-d'),
            'parentId' => null,
            'tasks' => $this->tasksList->getIds(),
            'catalogs' => $this->catalogs->getIds(),
            'videos' => $this->videosList->getIds(),
            'notes' => $this->notesList->getIds(),
            'bookmarks' => $this->bookmarksList->getIds(),
            'files' => $this->filesList->getIds(),
            'alarm' => $this->alarmId,
            'active' => $this->isActive,
            'order' => $this->createdAt->getTimestamp()
        ];
    }
}
