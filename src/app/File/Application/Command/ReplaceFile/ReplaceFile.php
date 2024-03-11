<?php

namespace App\File\Application\Command\ReplaceFile;

use App\Core\Cqrs\CommandHandler;
use App\File\Application\Command\ModifyFileCommand;
use App\File\Application\UploadedFileInterface;
use App\Shared\Domain\Entity\FileId;

/**
 * Podmienia plik
 */
#[CommandHandler(ReplaceFileHandler::class)]
class ReplaceFile extends ModifyFileCommand
{
    private UploadedFileInterface $file;

    public function __construct(FileId $id, UploadedFileInterface $file)
    {
        parent::__construct($id);
        $this->file = $file;
    }

    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }
}
