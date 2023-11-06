<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\List\AlarmsGroupsListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AlarmsGroupsList implements AlarmsGroupsListInterface
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

    public function sync(AlarmsGroupId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(AlarmsGroupId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }

    public function detach(AlarmsGroupId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }
}
