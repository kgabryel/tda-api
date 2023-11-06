<?php

namespace App\Note\Application\Command\UpdateName;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;
use App\Shared\Domain\Entity\NoteId;

/**
 * Aktualizuje nazwe notatki
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifyNoteCommand
{
    private string $name;

    public function __construct(NoteId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
