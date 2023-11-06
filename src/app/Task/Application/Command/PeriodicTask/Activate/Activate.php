<?php

namespace App\Task\Application\Command\PeriodicTask\Activate;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Command\PeriodicTask\ModifyPeriodicTaskCommand;

/**
 * Aktywuje zadanie okresowe
 */
#[CommandHandler(ActivateHandler::class)]
class Activate extends ModifyPeriodicTaskCommand
{
    private ActivateAction $action;

    public function __construct(TasksGroupId $id, ActivateAction $action)
    {
        parent::__construct($id);
        $this->action = $action;
    }

    public function getAction(): ActivateAction
    {
        return $this->action;
    }
}
