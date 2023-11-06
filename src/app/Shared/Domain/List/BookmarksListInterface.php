<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\SyncResult;

interface BookmarksListInterface
{
    public function getIds(): array;

    public function sync(BookmarkId ...$ids): SyncResult;

    public function detach(BookmarkId $id): bool;

    public function attach(BookmarkId $id): bool;
}
