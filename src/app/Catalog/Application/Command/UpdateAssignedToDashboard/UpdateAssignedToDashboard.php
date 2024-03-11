<?php

namespace App\Catalog\Application\Command\UpdateAssignedToDashboard;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Przypina lub odpina katalog z dashboardu
 */
#[CommandHandler(UpdateAssignedToDashboardHandler::class)]
class UpdateAssignedToDashboard extends ModifyCatalogCommand
{
    private bool $assignedToDashboard;

    public function __construct(CatalogId $id, bool $assignedToDashboard)
    {
        parent::__construct($id);
        $this->assignedToDashboard = $assignedToDashboard;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }
}
