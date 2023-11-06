<?php

namespace App\Core\Cqrs;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ListenEvent
{
    private string $eventName;

    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }
}
