<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\SyncResult;

interface TasksGroupsListInterface
{
    public function getIds(): array;

    public function sync(TasksGroupId ...$ids): SyncResult;

    public function detach(TasksGroupId $id): bool;
}
