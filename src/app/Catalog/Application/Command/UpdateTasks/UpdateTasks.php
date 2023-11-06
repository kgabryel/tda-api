<?php

namespace App\Catalog\Application\Command\UpdateTasks;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Aktualizuje liste zadan przypisanych do katalogu
 */
#[CommandHandler(UpdateTasksHandler::class)]
class UpdateTasks extends ModifyCatalogCommand
{
    private array $tasks;

    public function __construct(CatalogId $id, string ...$tasks)
    {
        parent::__construct($id);
        $this->tasks = $tasks;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
