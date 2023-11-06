<?php

namespace App\File\Application;

use App\File\Application\ViewModel\File;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

interface ReadRepository
{
    public function findById(FileId $fileId, UserId $userId): File;

    public function find(UserId $userId, FileId ...$filesIds): array;

    public function findAll(UserId $userId): array;
}
