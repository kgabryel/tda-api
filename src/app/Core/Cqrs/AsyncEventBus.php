<?php

namespace App\Core\Cqrs;

use App\Core\BusUtils;
use Ds\Queue;

class AsyncEventBus
{
    private Queue $events;
    private EventsConfig $eventsConfig;
    private BusUtils $busUtils;

    public function __construct(BusUtils $busUtils, EventsConfig $eventsConfig)
    {
        $this->events = new Queue();
        $this->eventsConfig = $eventsConfig;
        $this->busUtils = $busUtils;
    }

    public function addEvent(AsyncEvent $event): void
    {
        $handlers = $this->eventsConfig->getHandlers($event::class);
        foreach ($handlers as $handler) {
            $this->events->push(new Pair($event, $handler));
        }
    }

    public function run(): void
    {
        while (!$this->events->isEmpty()) {
            /** @var Pair $pair */
            $pair = $this->events->pop();
            $handlerInstance = $this->busUtils->createObject($pair->getHandlerName());
            $handlerInstance->handle($pair->getEvent());
            /**
             * angular ma problemy z zadaniami na ten sam adres w krotkich odstepach czasu
             * odstep dodany zeby nie laczyc eventow
             */
            usleep(300000);
        }
    }
}
