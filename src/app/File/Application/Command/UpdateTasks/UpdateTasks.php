<?php

namespace App\File\Application\Command\UpdateTasks;

use App\Core\Cqrs\CommandHandler;
use App\File\Application\Command\ModifyFileCommand;
use App\Shared\Domain\Entity\FileId;

/**
 * Aktualizuje liste zadan przypisanych do zakladki
 */
#[CommandHandler(UpdateTasksHandler::class)]
class UpdateTasks extends ModifyFileCommand
{
    private array $tasks;

    public function __construct(FileId $id, string ...$tasks)
    {
        parent::__construct($id);
        $this->tasks = $tasks;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
