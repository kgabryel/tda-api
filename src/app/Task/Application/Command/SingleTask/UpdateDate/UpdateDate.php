<?php

namespace App\Task\Application\Command\SingleTask\UpdateDate;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\ModifySingleTaskCommand;
use DateTimeImmutable;

/**
 * Aktualizuje date zadania pojedynczego
 */
#[CommandHandler(UpdateDateHandler::class)]
class UpdateDate extends ModifySingleTaskCommand
{
    private ?DateTimeImmutable $date;

    public function __construct(TaskId $id, ?DateTimeImmutable $date)
    {
        parent::__construct($id);
        $this->date = $date;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }
}
