<?php

namespace App\File\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\File\Domain\Event\FileReplaced;

#[ListenEvent(FileReplaced::class)]
class FileReplacedHandler extends EventHandler
{
    public function handle(FileReplaced $event): void
    {
        $this->fileManager->replaceFile($event->getFileId(), $event->getPath(), $event->getFile());
    }
}
