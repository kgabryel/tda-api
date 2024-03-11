<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\List\FilesListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FilesList implements FilesListInterface
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

    public function detach(FileId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }

    public function attach(FileId $id): bool
    {
        return count($this->connection->sync([$id->getValue()], false)['attached'] ?? []) === 1;
    }

    public function sync(FileId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(FileId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }
}
