<?php

namespace App\Task\Application\Command\PeriodicTask\Deactivate;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Command\PeriodicTask\ModifyPeriodicTaskCommand;

/**
 * Dezaktywuje zadanie okresowe
 */
#[CommandHandler(DeactivateHandler::class)]
class Deactivate extends ModifyPeriodicTaskCommand
{
    private DeactivateAction $action;

    public function __construct(TasksGroupId $id, DeactivateAction $action)
    {
        parent::__construct($id);
        $this->action = $action;
    }

    public function getAction(): DeactivateAction
    {
        return $this->action;
    }
}
