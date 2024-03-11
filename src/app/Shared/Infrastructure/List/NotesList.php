<?php

namespace App\Shared\Infrastructure\List;

use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\List\NotesListInterface;
use App\Shared\Domain\SyncResult;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotesList implements NotesListInterface
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

    public function detach(NoteId $id): bool
    {
        return $this->connection->detach($id->getValue()) === 1;
    }

    public function attach(NoteId $id): bool
    {
        return count($this->connection->sync([$id->getValue()], false)['attached'] ?? []) === 1;
    }

    public function sync(NoteId ...$ids): SyncResult
    {
        $result = $this->connection->sync(array_map(static fn(NoteId $id) => $id->getValue(), $ids));

        return new SyncResult($result['attached'], $result['detached']);
    }
}
