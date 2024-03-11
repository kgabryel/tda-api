<?php

namespace App\Note\Domain;

use App\Note\Domain\Entity\Note;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;

interface WriteRepository
{
    public function findById(NoteId $noteId, UserId $userId): Note;
}
