<?php

namespace App\Task\Application\Command\RemoveNote;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\NoteId;

/**
 * Odpina notatke od zadania
 */
#[CommandHandler(RemoveNoteHandler::class)]
class RemoveNote implements Command
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
