<?php

namespace App\File\Application\Command\UpdateAssigmentToDashboard;

use App\Core\Cqrs\CommandHandler;
use App\File\Application\Command\ModifyFileCommand;
use App\Shared\Domain\Entity\FileId;

/**
 * Przypina lub odpina plik z dashboardu
 */
#[CommandHandler(UpdateAssignedToDashboardHandler::class)]
class UpdateAssignedToDashboard extends ModifyFileCommand
{
    private bool $assignedToDashboard;

    public function __construct(FileId $id, bool $assignedToDashboard)
    {
        parent::__construct($id);
        $this->assignedToDashboard = $assignedToDashboard;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }
}
