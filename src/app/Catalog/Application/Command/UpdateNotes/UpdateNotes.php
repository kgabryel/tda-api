<?php

namespace App\Catalog\Application\Command\UpdateNotes;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\NoteId;

/**
 * Aktualizuje liste notatek przypisanych do katalogu
 */
#[CommandHandler(UpdateNotesHandler::class)]
class UpdateNotes extends ModifyCatalogCommand
{
    private array $notes;

    public function __construct(CatalogId $id, NoteId ...$notes)
    {
        parent::__construct($id);
        $this->notes = $notes;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }
}
