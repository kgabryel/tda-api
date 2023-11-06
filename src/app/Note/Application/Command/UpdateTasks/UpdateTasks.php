<?php

namespace App\Note\Application\Command\UpdateTasks;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;
use App\Shared\Domain\Entity\NoteId;

/**
 * Aktualizuje liste zadan przypisanych do notatki
 */
#[CommandHandler(UpdateTasksHandler::class)]
class UpdateTasks extends ModifyNoteCommand
{
    private array $tasks;

    public function __construct(NoteId $id, string ...$tasks)
    {
        parent::__construct($id);
        $this->tasks = $tasks;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
