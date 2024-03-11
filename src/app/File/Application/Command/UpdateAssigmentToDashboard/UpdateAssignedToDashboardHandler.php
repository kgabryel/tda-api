<?php

namespace App\File\Application\Command\UpdateAssigmentToDashboard;

use App\File\Application\Command\ModifyFileHandler;
use App\File\Domain\Event\Updated;

class UpdateAssignedToDashboardHandler extends ModifyFileHandler
{
    public function handle(UpdateAssignedToDashboard $command): void
    {
        $file = $this->getFile($command->getFileId());
        if ($file->updateAssignedToDashboard($command->isAssignedToDashboard())) {
            $this->eventEmitter->emit(new Updated($file));
        }
    }
}
