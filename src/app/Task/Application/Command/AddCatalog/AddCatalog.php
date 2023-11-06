<?php

namespace App\Task\Application\Command\AddCatalog;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Przypina katalog do zadania
 */
#[CommandHandler(AddCatalogHandler::class)]
class AddCatalog implements Command
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
