<?php

namespace App\Task\Domain\Event;

use App\Bookmark\Domain\Event\BookmarksModified;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane zakladki zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania
 */
class BookmarksAssigmentChanged implements BookmarksModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, BookmarkId ...$ids)
    {
        $this->ids = $ids;
        $this->userId = $userId;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
