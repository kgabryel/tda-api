<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\SyncResult;

interface TasksListInterface
{
    public function getIds(): array;

    public function sync(TaskId ...$ids): SyncResult;

    public function detach(TaskId $id): bool;

    public function delete(): void;

    public function disconnect(): void;
}
