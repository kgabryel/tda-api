<?php

namespace App\Catalog\Application\Command\RemoveTask;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Odpina zadanie od katalogu
 */
#[CommandHandler(RemoveTaskHandler::class)]
class RemoveTask extends ModifyCatalogCommand
{
    private string $taskId;

    public function __construct(CatalogId $id, string $taskId)
    {
        parent::__construct($id);
        $this->taskId = $taskId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }
}
