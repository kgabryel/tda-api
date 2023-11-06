<?php

namespace App\Note\Application\Command\UpdateAssignedToDashboard;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;
use App\Shared\Domain\Entity\NoteId;

/**
 * Przypina lub odpina notatke z dashboardu
 */
#[CommandHandler(UpdateAssignedToDashboardHandler::class)]
class UpdateAssignedToDashboard extends ModifyNoteCommand
{
    private bool $assignedToDashboard;

    public function __construct(NoteId $id, bool $assignedToDashboard)
    {
        parent::__construct($id);
        $this->assignedToDashboard = $assignedToDashboard;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }
}
