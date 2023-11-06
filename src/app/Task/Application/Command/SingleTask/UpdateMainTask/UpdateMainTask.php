<?php

namespace App\Task\Application\Command\SingleTask\UpdateMainTask;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\ModifySingleTaskCommand;

/**
 * Aktualizuje zadanie glowne, Przypina nowe lub odpina aktualne
 */
#[CommandHandler(UpdateMainTaskHandler::class)]
class UpdateMainTask extends ModifySingleTaskCommand
{
    private ?TaskId $mainTask;

    public function __construct(TaskId $id, ?TaskId $mainTask)
    {
        parent::__construct($id);
        $this->mainTask = $mainTask;
    }

    public function getMainTask(): ?TaskId
    {
        return $this->mainTask;
    }
}
