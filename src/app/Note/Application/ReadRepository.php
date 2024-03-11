<?php

namespace App\Note\Application;

use App\Note\Application\ViewModel\Note;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;

interface ReadRepository
{
    public function findById(NoteId $noteId, UserId $userId): Note;

    public function find(UserId $userId, NoteId ...$notesIds): array;

    public function findAll(UserId $userId): array;
}
