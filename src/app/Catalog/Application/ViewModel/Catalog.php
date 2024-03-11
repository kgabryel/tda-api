<?php

namespace App\Catalog\Application\ViewModel;

use App\Shared\Application\Dto\AlarmsGroupsIdsList;
use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Dto\VideosIdsList;
use JsonSerializable;

class Catalog implements JsonSerializable
{
    private int $id;
    private string $name;
    private bool $assignedToDashboard;
    private SingleAlarmsIdsList $alarmsList;
    private AlarmsGroupsIdsList $alarmsGroupsList;
    private SingleTasksIdsList $tasksList;
    private TasksGroupsIdsList $tasksGroupsList;
    private NotesIdsList $notesList;
    private BookmarksIdsList $bookmarksList;
    private FilesIdsList $filesList;
    private VideosIdsList $videosList;

    public function __construct(
        int $id,
        string $name,
        bool $assignedToDashboard,
        SingleAlarmsIdsList $alarmsList,
        AlarmsGroupsIdsList $alarmsGroupsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        NotesIdsList $notesList,
        BookmarksIdsList $bookmarksList,
        FilesIdsList $filesList,
        VideosIdsList $videosList
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->assignedToDashboard = $assignedToDashboard;
        $this->alarmsList = $alarmsList;
        $this->alarmsGroupsList = $alarmsGroupsList;
        $this->tasksList = $tasksList;
        $this->tasksGroupsList = $tasksGroupsList;
        $this->notesList = $notesList;
        $this->bookmarksList = $bookmarksList;
        $this->filesList = $filesList;
        $this->videosList = $videosList;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'assignedToDashboard' => $this->assignedToDashboard,
            'alarms' => array_merge($this->alarmsList->getIds(), $this->alarmsGroupsList->getIds()),
            'tasks' => array_merge($this->tasksList->getIds(), $this->tasksGroupsList->getIds()),
            'notes' => $this->notesList->getIds(),
            'bookmarks' => $this->bookmarksList->getIds(),
            'files' => $this->filesList->getIds(),
            'videos' => $this->videosList->getIds()
        ];
    }
}
