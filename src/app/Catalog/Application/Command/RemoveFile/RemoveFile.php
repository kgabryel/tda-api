<?php

namespace App\Catalog\Application\Command\RemoveFile;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;

/**
 * Odpina plik od katalogu
 */
#[CommandHandler(RemoveFileHandler::class)]
class RemoveFile extends ModifyCatalogCommand
{
    private FileId $fileId;

    public function __construct(CatalogId $id, FileId $fileId)
    {
        parent::__construct($id);
        $this->fileId = $fileId;
    }

    public function getFileId(): FileId
    {
        return $this->fileId;
    }
}
