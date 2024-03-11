<?php

namespace App\Core\Cqrs;

use App\Core\BusUtils;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\Shared\Application\Command\Command;
use ReflectionClass;

class CommandBus
{
    private BusUtils $busUtils;

    public function __construct(BusUtils $busUtils)
    {
        $this->busUtils = $busUtils;
    }

    /**
     * @throws NoHandlerException
     */
    public function handle(Command $command): void
    {
        $this->executeHandle($command);
    }

    private function executeHandle(Command $command): mixed
    {
        $reflector = new ReflectionClass($command::class);
        $handler = $reflector->getAttributes(CommandHandler::class)[0] ?? null;
        if ($handler === null) {
            throw new NoHandlerException(sprintf('Class "%s" has no assigned handler.', $command::class));
        }
        $handlerName = $handler->newInstance()->getHandlerName();
        $handlerInstance = $this->busUtils->createObject($handlerName);
        if ($handlerInstance instanceof AssignedUserCommandHandler) {
            $handlerInstance->setUserId($this->busUtils->getUserId());
        }

        return $handlerInstance->handle($command);
    }

    public function handleWithResult(Command $command): mixed
    {
        return $this->executeHandle($command);
    }
}
