<?php

namespace App\Note\Application\Command\UpdateContent;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;
use App\Shared\Domain\Entity\NoteId;

/**
 * Aktualizuje tresc notatki
 */
#[CommandHandler(UpdateContentHandler::class)]
class UpdateContent extends ModifyNoteCommand
{
    private string $content;

    public function __construct(NoteId $id, string $content)
    {
        parent::__construct($id);
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
