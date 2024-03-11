<?php

namespace App\File\Domain\Event;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\FileId;

/**
 * Plik zostal usuniety, nalezy usunac go z bazy danych
 */
class Deleted implements Event
{
    private FileId $fileId;

    public function __construct(FileId $fileId)
    {
        $this->fileId = $fileId;
    }

    public function getFileId(): FileId
    {
        return $this->fileId;
    }
}
