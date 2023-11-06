<?php

namespace App\Core\Cqrs;

class EventEmitter
{
    private EventBus $eventBus;
    private AsyncEventBus $asyncEventBus;

    public function __construct(EventBus $eventBus, AsyncEventBus $asyncEventBus)
    {
        $this->eventBus = $eventBus;
        $this->asyncEventBus = $asyncEventBus;
    }

    public function emit(Event|AsyncEvent $event): void
    {
        if ($event instanceof AsyncEvent) {
            $this->asyncEventBus->addEvent($event);
        } else {
            $this->eventBus->emit($event);
        }
    }
}
