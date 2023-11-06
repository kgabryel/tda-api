<?php

namespace App\File\Application\Command\UpdateName;

use App\File\Application\Command\ModifyFileHandler;
use App\File\Domain\Event\Updated;

class UpdateNameHandler extends ModifyFileHandler
{
    public function handle(UpdateName $command): void
    {
        $file = $this->getFile($command->getFileId());
        if ($file->updateName($command->getName())) {
            $this->eventEmitter->emit(new Updated($file));
        }
    }
}
