<?php

namespace App\Task\Application\Command\AddNote;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\NoteId;

/**
 * Przypina notatke do zadania
 */
#[CommandHandler(AddNoteHandler::class)]
class AddNote implements Command
{
    private string $taskId;
    private NoteId $noteId;

    public function __construct(string $taskId, NoteId $noteId)
    {
        $this->taskId = $taskId;
        $this->noteId = $noteId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getNoteId(): NoteId
    {
        return $this->noteId;
    }
}
