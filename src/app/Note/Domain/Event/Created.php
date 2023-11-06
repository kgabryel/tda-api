<?php

namespace App\Note\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Note\Domain\Entity\Note;

/**
 * Notatka zostala dodana, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Created implements AsyncEvent
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
