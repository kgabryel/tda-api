<?php

namespace App\File\Application\Command;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\FileId;

abstract class ModifyFileCommand implements Command
{
    protected FileId $fileId;

    public function __construct(FileId $fileId)
    {
        $this->fileId = $fileId;
    }

    public function getFileId(): FileId
    {
        return $this->fileId;
    }
}
