<?php

namespace App\Task\Domain\Event;

use App\Note\Domain\Event\NotesModified;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane notatki zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania
 */
class NotesAssigmentChanged implements NotesModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, NoteId ...$ids)
    {
        $this->ids = $ids;
        $this->userId = $userId;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
