<?php

namespace App\Catalog\Application;

use App\Catalog\Domain\Entity\Catalog;
use App\Shared\Application\Dto\AlarmsGroupsIdsList;
use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Dto\VideosIdsList;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

interface CatalogManagerInterface
{
    public function update(Catalog $catalog): void;

    public function delete(CatalogId $catalogId): void;

    public function create(
        string $name,
        bool $assignedToDashboard,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        SingleAlarmsIdsList $alarmsList,
        AlarmsGroupsIdsList $alarmsGroupsList,
        BookmarksIdsList $bookmarksList,
        NotesIdsList $notesList,
        FilesIdsList $filesList,
        VideosIdsList $videosList,
        UserId $userId
    ): Catalog;
}
