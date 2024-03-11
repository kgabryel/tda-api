<?php

namespace App\Core\Cqrs;

use App\Core\BusUtils;

class EventBus
{
    private BusUtils $busUtils;
    private EventsConfig $eventsConfig;

    public function __construct(BusUtils $busUtils, EventsConfig $eventsConfig)
    {
        $this->eventsConfig = $eventsConfig;
        $this->busUtils = $busUtils;
    }

    public function emit(Event $event): void
    {
        $handlers = $this->eventsConfig->getHandlers($event::class);
        foreach ($handlers as $handler) {
            $handlerInstance = $this->busUtils->createObject($handler);
            $handlerInstance->handle($event);
        }
    }
}
