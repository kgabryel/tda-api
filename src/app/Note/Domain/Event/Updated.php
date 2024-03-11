<?php

namespace App\Note\Domain\Event;

use App\Core\Cqrs\Event;
use App\Note\Domain\Entity\Note;

/**
 * Notatka zostala zmodyfikowana, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
{
    private Note $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function getNote(): Note
    {
        return $this->note;
    }
}
