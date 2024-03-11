<?php

namespace App\User\Application\Command\ChangeSettingsValue;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\AssignedUserCommandHandler;
use App\User\Application\SettingsManagerInterface;

class ChangeSettingsValueHandler extends AssignedUserCommandHandler
{
    private SettingsManagerInterface $settingsManager;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        SettingsManagerInterface $settingsManager
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->settingsManager = $settingsManager;
    }

    public function handle(ChangeSettingsValue $command): void
    {
        $this->settingsManager->changeValue($this->userId, $command->getKey(), $command->getValue());
    }
}
