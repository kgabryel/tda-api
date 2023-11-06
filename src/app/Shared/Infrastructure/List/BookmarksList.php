<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\List\BookmarksListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BookmarksList implements BookmarksListInterface
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

    public function detach(BookmarkId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }

    public function attach(BookmarkId $id): bool
    {
        return count($this->connection->sync([$id->getValue()], false)['attached'] ?? []) === 1;
    }

    public function sync(BookmarkId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(BookmarkId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }
}
