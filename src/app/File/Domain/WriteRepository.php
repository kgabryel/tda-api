<?php

namespace App\File\Domain;

use App\File\Domain\Entity\File;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

interface WriteRepository
{
    public function findById(FileId $fileId, UserId $userId): File;
}
