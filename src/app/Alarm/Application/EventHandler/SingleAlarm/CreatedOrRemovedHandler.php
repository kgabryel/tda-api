<?php

namespace App\Alarm\Application\EventHandler\SingleAlarm;

use App\Alarm\Domain\Event\CatalogsAssigmentChanged;
use App\Alarm\Domain\Event\Created;
use App\Alarm\Domain\Event\Removed;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Shared\Domain\Entity\CatalogId;

#[ListenEvent(Created::class)]
#[ListenEvent(Removed::class)]
class CreatedOrRemovedHandler
{
    private EventEmitter $eventEmitter;

    public function __construct(EventEmitter $eventEmitter)
    {
        $this->eventEmitter = $eventEmitter;
    }

    public function handle(Created|Removed $event): void
    {
        $catalogs = $event->getAlarm()->getCatalogsIds();
        $this->eventEmitter->emit(
            new CatalogsAssigmentChanged(
                $event->getAlarm()->getUserId(),
                ...array_map(static fn(string $id) => new CatalogId($id), $catalogs)
            )
        );
    }
}
