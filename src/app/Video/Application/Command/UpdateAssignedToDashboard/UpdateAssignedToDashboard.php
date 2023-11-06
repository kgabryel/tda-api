<?php

namespace App\Video\Application\Command\UpdateAssignedToDashboard;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\Command\ModifyVideoCommand;

/**
 * Przypina lub odpina film z dashboardu
 */
#[CommandHandler(UpdateAssignedToDashboardHandler::class)]
class UpdateAssignedToDashboard extends ModifyVideoCommand
{
    private bool $assignedToDashboard;

    public function __construct(VideoId $id, bool $assignedToDashboard)
    {
        parent::__construct($id);
        $this->assignedToDashboard = $assignedToDashboard;
    }

    public function getAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }
}
