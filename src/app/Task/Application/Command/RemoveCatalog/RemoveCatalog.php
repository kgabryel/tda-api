<?php

namespace App\Task\Application\Command\RemoveCatalog;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Odpina katalog od zadania
 */
#[CommandHandler(RemoveCatalogHandler::class)]
class RemoveCatalog implements Command
{
    private string $taskId;
    private CatalogId $catalogId;

    public function __construct(string $taskId, CatalogId $catalogId)
    {
        $this->taskId = $taskId;
        $this->catalogId = $catalogId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getCatalogId(): CatalogId
    {
        return $this->catalogId;
    }
}
