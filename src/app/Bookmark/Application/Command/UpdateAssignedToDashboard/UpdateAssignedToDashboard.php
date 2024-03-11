<?php

namespace App\Bookmark\Application\Command\UpdateAssignedToDashboard;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Przypina lub odpina zakladke z dashboardu
 */
#[CommandHandler(UpdateAssignedToDashboardHandler::class)]
class UpdateAssignedToDashboard extends ModifyBookmarkCommand
{
    private bool $assignedToDashboard;

    public function __construct(BookmarkId $id, bool $assignedToDashboard)
    {
        parent::__construct($id);
        $this->assignedToDashboard = $assignedToDashboard;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }
}
