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

class SingleTask implements JsonSerializable
{
    private string $id;
    private string $name;
    private ?string $content;
    private ?DateTimeImmutable $date;
    private int $statusId;
    private ?string $parentId;
    private ?string $groupId;
    private ?string $alarmId;
    private CatalogsIdsList $catalogs;
    private SingleTasksIdsList $subtasksList;
    private NotesIdsList $notesList;
    private BookmarksIdsList $bookmarksList;
    private FilesIdsList $filesList;
    private VideosIdsList $videosList;
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $id,
        string $name,
        ?string $content,
        ?DateTimeImmutable $date,
        int $statusId,
        ?string $parentId,
        ?string $groupId,
        ?string $alarmId,
        CatalogsIdsList $catalogs,
        SingleTasksIdsList $subtasksList,
        NotesIdsList $notesList,
        BookmarksIdsList $bookmarksList,
        FilesIdsList $filesList,
        VideosIdsList $videosList,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->date = $date;
        $this->statusId = $statusId;
        $this->parentId = $parentId;
        $this->groupId = $groupId;
        $this->alarmId = $alarmId;
        $this->catalogs = $catalogs;
        $this->subtasksList = $subtasksList;
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
            'date' => $this->date?->format(DATE_RFC2822),
            'status' => $this->statusId,
            'group' => $this->groupId,
            'periodic' => false,
            'parentId' => $this->parentId,
            'subtasks' => $this->subtasksList->getIds(),
            'catalogs' => $this->catalogs->getIds(),
            'videos' => $this->videosList->getIds(),
            'notes' => $this->notesList->getIds(),
            'bookmarks' => $this->bookmarksList->getIds(),
            'files' => $this->filesList->getIds(),
            'alarm' => $this->alarmId,
            'order' => $this->createdAt->getTimestamp()
        ];
    }
}
