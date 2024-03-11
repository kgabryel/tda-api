<?php

namespace App\Catalog\Application\Command\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Application\Dto\AlarmsIdsList;
use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Application\Dto\VideosIdsList;

/**
 * Tworzy nowy katalog
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private string $name;
    private bool $assignedToDashboard;
    private TasksIdsList $tasks;
    private NotesIdsList $notes;
    private BookmarksIdsList $bookmarks;
    private FilesIdsList $files;
    private VideosIdsList $videos;
    private AlarmsIdsList $alarms;

    public function __construct(
        string $name,
        bool $assignedToDashboard,
        TasksIdsList $tasks,
        NotesIdsList $notes,
        BookmarksIdsList $bookmarks,
        FilesIdsList $files,
        VideosIdsList $videos,
        AlarmsIdsList $alarms,
    ) {
        $this->name = $name;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->tasks = $tasks;
        $this->notes = $notes;
        $this->bookmarks = $bookmarks;
        $this->files = $files;
        $this->videos = $videos;
        $this->alarms = $alarms;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }

    public function getTasks(): TasksIdsList
    {
        return $this->tasks;
    }

    public function getNotes(): NotesIdsList
    {
        return $this->notes;
    }

    public function getBookmarks(): BookmarksIdsList
    {
        return $this->bookmarks;
    }

    public function getFiles(): FilesIdsList
    {
        return $this->files;
    }

    public function getVideos(): VideosIdsList
    {
        return $this->videos;
    }

    public function getAlarms(): AlarmsIdsList
    {
        return $this->alarms;
    }
}
