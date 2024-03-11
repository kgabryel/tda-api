<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\List\TasksListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TasksList implements TasksListInterface
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

    public function sync(TaskId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(TaskId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }

    public function detach(TaskId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }

    public function delete(): void
    {
        $this->connection->delete();
    }

    public function disconnect(): void
    {
        $this->connection->update(['parent_id' => null]);
    }
}
