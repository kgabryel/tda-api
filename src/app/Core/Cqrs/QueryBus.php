<?php

namespace App\Core\Cqrs;

use App\Core\BusUtils;
use App\Shared\Application\Query\AssignedUserQueryHandler;
use App\Shared\Application\Query\Query;
use ReflectionClass;

class QueryBus
{
    private BusUtils $busUtils;

    public function __construct(BusUtils $busUtils)
    {
        $this->busUtils = $busUtils;
    }

    /**
     * @throws NoHandlerException
     */
    public function handle(Query $query): mixed
    {
        $reflector = new ReflectionClass($query::class);
        $handler = $reflector->getAttributes(QueryHandler::class)[0] ?? null;
        if ($handler === null) {
            throw new NoHandlerException(sprintf('Class "%s" has no assigned handler.', $query::class));
        }
        $handlerName = $handler->newInstance()->getHandlerName();
        $handlerInstance = $this->busUtils->createObject($handlerName);
        if ($handlerInstance instanceof AssignedUserQueryHandler) {
            $handlerInstance->setUserId($this->busUtils->getUserId());
        }

        return $handlerInstance->handle($query);
    }
}
