<?php

namespace App\Bookmark\Domain;

use App\Bookmark\Domain\Entity\Bookmark;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;

interface WriteRepository
{
    public function findById(BookmarkId $bookmarkId, UserId $userId): Bookmark;
}
