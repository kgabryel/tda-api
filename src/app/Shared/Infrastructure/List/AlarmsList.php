<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\List\AlarmsListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AlarmsList implements AlarmsListInterface
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

    public function sync(AlarmId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(AlarmId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }

    public function detach(AlarmId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }
}
