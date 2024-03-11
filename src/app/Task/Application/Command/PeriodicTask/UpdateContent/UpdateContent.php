<?php

namespace App\Task\Application\Command\PeriodicTask\UpdateContent;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Command\PeriodicTask\ModifyPeriodicTaskCommand;

/**
 * Aktualizuje tresc zadania okresowego
 */
#[CommandHandler(UpdateContentHandler::class)]
class UpdateContent extends ModifyPeriodicTaskCommand
{
    private ?string $content;

    public function __construct(TasksGroupId $id, string $content)
    {
        parent::__construct($id);
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
