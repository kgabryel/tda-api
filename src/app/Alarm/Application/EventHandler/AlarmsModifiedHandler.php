<?php

namespace App\Alarm\Application\EventHandler;

use App\Alarm\Application\Notificator;
use App\Alarm\Domain\Event\Port\AlarmsModified;
use App\Core\Cqrs\ListenEvent;

#[ListenEvent(AlarmsModified::class)]
class AlarmsModifiedHandler
{
    private Notificator $notificator;

    public function __construct(Notificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function handle(AlarmsModified $event): void
    {
        $this->notificator->alarmsChanges($event->getUserId(), ...$event->getIds());
    }
}
