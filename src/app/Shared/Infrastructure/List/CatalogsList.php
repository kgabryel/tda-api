<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\List\CatalogsListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CatalogsList implements CatalogsListInterface
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

    public function detach(CatalogId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }

    public function attach(CatalogId $id): bool
    {
        return count($this->connection->sync([$id->getValue()], false)['attached'] ?? []) === 1;
    }

    public function sync(CatalogId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(CatalogId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }
}
