<?php

namespace App\Color\Application\Command\Create;

use App\Color\Application\ColorManagerInterface;
use App\Color\Domain\Entity\ColorId;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;

class CreateHandler extends AssignedUserCommandHandler
{
    private ColorManagerInterface $colorManager;

    public function __construct(QueryBus $queryBus, EventEmitter $eventEmitter, ColorManagerInterface $colorManager)
    {
        parent::__construct($queryBus, $eventEmitter);
        $this->colorManager = $colorManager;
    }

    public function handle(Create $command): ColorId
    {
        $color = $this->colorManager->create($command->getName(), $command->getColor(), $this->userId);

        return $color->getColorId();
    }
}
