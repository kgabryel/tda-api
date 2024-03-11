<?php

namespace App\Note\Domain\Event;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\NoteId;

/**
 * Notatka zostala usunieta, nalezy usunac ja z bazy danych
 */
class Deleted implements Event
{
    private NoteId $noteId;

    public function __construct(NoteId $noteId)
    {
        $this->noteId = $noteId;
    }

    public function getNoteId(): NoteId
    {
        return $this->noteId;
    }
}
