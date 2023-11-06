<?php

namespace App\Task\Application\Command\SingleTask\UpdateContent;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\ModifySingleTaskCommand;

/**
 * Aktualizuje tresc zadania pojedynczego
 */
#[CommandHandler(UpdateContentHandler::class)]
class UpdateContent extends ModifySingleTaskCommand
{
    private string $content;

    public function __construct(TaskId $id, ?string $content)
    {
        parent::__construct($id);
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
