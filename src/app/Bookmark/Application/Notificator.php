<?php

namespace App\Bookmark\Application;

use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;

interface Notificator
{
    public function bookmarksChanges(UserId|int $user, BookmarkId ...$ids): void;
}
