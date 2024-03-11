<?php

namespace App\Alarm\Application\EventHandler;

use App\Alarm\Application\Notificator;
use App\Alarm\Domain\Event\Port\AlarmsGroupsModified;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(AlarmsGroupsModified::class)]
class AlarmsGroupsModifiedHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(AlarmsGroupsModified $event): void
    {
        $this->notificator->alarmsChanges($event->getUserId(), ...$event->getIds());
    }
}
