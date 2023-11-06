<?php

namespace App\Bookmark\Application;

use App\Bookmark\Domain\Entity\Bookmark;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;

interface BookmarkManagerInterface
{
    public function update(Bookmark $bookmark): void;

    public function delete(BookmarkId $bookmarkId): void;

    public function create(
        string $name,
        string $href,
        ?string $icon,
        bool $assignedToDashboard,
        Hex $textColor,
        Hex $backgroundColor,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): Bookmark;
}
