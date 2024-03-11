<?php

namespace App\Core\Cqrs;

class Pair
{
    private AsyncEvent $event;
    private string $handlerName;

    public function __construct(AsyncEvent $event, string $handlerName)
    {
        $this->event = $event;
        $this->handlerName = $handlerName;
    }

    public function getEvent(): AsyncEvent
    {
        return $this->event;
    }

    public function getHandlerName(): string
    {
        return $this->handlerName;
    }
}
