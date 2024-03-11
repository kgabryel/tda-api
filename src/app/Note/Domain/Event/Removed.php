<?php

namespace App\Note\Domain\Event;

use App\Core\Cqrs\Event;
use App\Note\Domain\Entity\Note;

/**
 * Notatka zostala usunieta, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Removed implements Event
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
