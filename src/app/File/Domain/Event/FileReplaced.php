<?php

namespace App\File\Domain\Event;

use App\Core\Cqrs\Event;
use App\File\Application\UploadedFileInterface;
use App\Shared\Domain\Entity\FileId;

/**
 * Plik zostal podsmieniony, nalezy podmienic zapisany plik
 */
class FileReplaced implements Event
{
    private FileId $fileId;
    private UploadedFileInterface $file;
    private string $path;

    public function __construct(FileId $fileId, UploadedFileInterface $file, string $path)
    {
        $this->fileId = $fileId;
        $this->file = $file;
        $this->path = $path;
    }

    public function getFileId(): FileId
    {
        return $this->fileId;
    }

    public function getFile(): UploadedFileInterface
    {
        return $this->file;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
