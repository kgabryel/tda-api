<?php

namespace App\Bookmark\Application;

use App\Bookmark\Application\ViewModel\Bookmark;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;

interface ReadRepository
{
    public function findById(BookmarkId $bookmarkId, UserId $userId): Bookmark;

    public function find(UserId $userId, BookmarkId ...$bookmarksIds): array;

    public function findAll(UserId $userId): array;
}
