<?php

namespace App\File\Application\Command\UpdateCatalogs;

use App\Core\Cqrs\CommandHandler;
use App\File\Application\Command\ModifyFileCommand;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;

/**
 * Aktualizuje liste katalogow przypisanych do pliku
 */
#[CommandHandler(UpdateCatalogsHandler::class)]
class UpdateCatalogs extends ModifyFileCommand
{
    private array $catalogs;

    public function __construct(FileId $id, CatalogId ...$catalogs)
    {
        parent::__construct($id);
        $this->catalogs = $catalogs;
    }

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }
}
