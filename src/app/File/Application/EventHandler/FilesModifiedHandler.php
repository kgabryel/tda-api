<?php

namespace App\File\Application\EventHandler;

use App\Core\Cqrs\ListenEvent;
use App\File\Application\Notificator;
use App\File\Domain\Event\FilesModified;

#[ListenEvent(FilesModified::class)]
class FilesModifiedHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(FilesModified $event): void
    {
        $this->notificator->filesChanges($event->getUserId(), ...$event->getIds());
    }
}
