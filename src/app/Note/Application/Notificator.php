<?php

namespace App\Note\Application;

use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;

interface Notificator
{
    public function notesChanges(UserId|int $user, NoteId ...$ids): void;
}
