<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\SyncResult;

interface FilesListInterface
{
    public function getIds(): array;

    public function sync(FileId ...$ids): SyncResult;

    public function detach(FileId $id): bool;

    public function attach(FileId $id): bool;
}
