<?php

namespace App\Catalog\Application\Command\UpdateFiles;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;

/**
 * Aktualizuje liste plikow przypisanych do katalogu
 */
#[CommandHandler(UpdateFilesHandler::class)]
class UpdateFiles extends ModifyCatalogCommand
{
    private array $files;

    public function __construct(CatalogId $id, FileId ...$files)
    {
        parent::__construct($id);
        $this->files = $files;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
