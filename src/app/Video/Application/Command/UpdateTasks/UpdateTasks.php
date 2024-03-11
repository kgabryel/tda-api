<?php

namespace App\Video\Application\Command\UpdateTasks;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\Command\ModifyVideoCommand;

/**
 * Aktualizuje liste zadan przypisanych do filmu
 */
#[CommandHandler(UpdateTasksHandler::class)]
class UpdateTasks extends ModifyVideoCommand
{
    private array $tasks;

    public function __construct(VideoId $id, string ...$tasks)
    {
        parent::__construct($id);
        $this->tasks = $tasks;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
