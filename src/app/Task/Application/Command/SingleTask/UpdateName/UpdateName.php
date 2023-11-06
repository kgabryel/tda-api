<?php

namespace App\Task\Application\Command\SingleTask\UpdateName;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\ModifySingleTaskCommand;

/**
 * Aktualizuje nazwe zadania pojedynczego
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifySingleTaskCommand
{
    private string $name;

    public function __construct(TaskId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
