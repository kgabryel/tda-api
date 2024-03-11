<?php

namespace App\Catalog\Application\Command\RemoveNote;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\NoteId;

/**
 * Odpina notatke od katalogu
 */
#[CommandHandler(RemoveNoteHandler::class)]
class RemoveNote extends ModifyCatalogCommand
{
    private NoteId $noteId;

    public function __construct(CatalogId $id, NoteId $noteId)
    {
        parent::__construct($id);
        $this->noteId = $noteId;
    }

    public function getNoteId(): NoteId
    {
        return $this->noteId;
    }
}
