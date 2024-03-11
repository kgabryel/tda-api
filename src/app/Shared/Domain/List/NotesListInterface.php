<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\SyncResult;

interface NotesListInterface
{
    public function getIds(): array;

    public function sync(NoteId ...$ids): SyncResult;

    public function detach(NoteId $id): bool;

    public function attach(NoteId $id): bool;
}
