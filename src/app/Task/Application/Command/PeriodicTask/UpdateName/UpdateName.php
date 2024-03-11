<?php

namespace App\Task\Application\Command\PeriodicTask\UpdateName;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Command\PeriodicTask\ModifyPeriodicTaskCommand;

/**
 * Aktualizuje nazwe zadania okresowego
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifyPeriodicTaskCommand
{
    private string $name;

    public function __construct(TasksGroupId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
