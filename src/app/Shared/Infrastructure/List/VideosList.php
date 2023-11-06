<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\VideoId;
use App\Shared\Domain\List\VideosListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VideosList implements VideosListInterface
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

    public function detach(VideoId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }

    public function attach(VideoId $id): bool
    {
        return count($this->connection->sync([$id->getValue()], false)['attached'] ?? []) === 1;
    }

    public function sync(VideoId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(VideoId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }
}
