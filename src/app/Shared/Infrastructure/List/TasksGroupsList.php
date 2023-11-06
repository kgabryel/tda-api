<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\List\TasksGroupsListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TasksGroupsList implements TasksGroupsListInterface
{
    private BelongsToMany $connection;

    public function __construct(BelongsToMany $connection)
    {
        $this->connection = $connection;
    }

    public function getIds(): array
    {
        return $this->connection->pluck('id')->toArray();
    }

    public function sync(TasksGroupId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(TasksGroupId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }

    public function detach(TasksGroupId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }
}
