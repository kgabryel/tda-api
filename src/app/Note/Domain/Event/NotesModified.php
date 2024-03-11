<?php

namespace App\Note\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane notatki zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania lub katalogu
 */
interface NotesModified extends AsyncEvent
{
    public function getIds(): array;

    public function getUserId(): UserId;
}
