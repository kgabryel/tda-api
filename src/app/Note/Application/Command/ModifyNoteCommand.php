<?php

namespace App\Note\Application\Command;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\NoteId;

abstract class ModifyNoteCommand implements Command
{
    protected NoteId $noteId;

    public function __construct(NoteId $noteId)
    {
        $this->noteId = $noteId;
    }

    public function getNoteId(): NoteId
    {
        return $this->noteId;
    }
}
