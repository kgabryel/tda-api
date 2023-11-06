<?php

namespace App\Shared\Application\Command;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;

abstract class CommandHandler
{
    protected QueryBus $queryBus;
    protected EventEmitter $eventEmitter;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter)
    {
        $this->queryBus = $queryBus;
        $this->eventEmitter = $eventEmitter;
    }
}
