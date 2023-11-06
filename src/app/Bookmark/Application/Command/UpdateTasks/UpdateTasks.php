<?php

namespace App\Bookmark\Application\Command\UpdateTasks;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Aktualizuje liste zadan przypisanych do zakladki
 */
#[CommandHandler(UpdateTasksHandler::class)]
class UpdateTasks extends ModifyBookmarkCommand
{
    private array $tasks;

    public function __construct(BookmarkId $id, string ...$tasks)
    {
        parent::__construct($id);
        $this->tasks = $tasks;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
