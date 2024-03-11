<?php

namespace App\Note\Application\Command\UpdateCatalogs;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\NoteId;

/**
 * Aktualizuje liste katalogow przypisanych do notatki
 */
#[CommandHandler(UpdateCatalogsHandler::class)]
class UpdateCatalogs extends ModifyNoteCommand
{
    private array $catalogs;

    public function __construct(NoteId $id, CatalogId ...$catalogs)
    {
        parent::__construct($id);
        $this->catalogs = $catalogs;
    }

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }
}
