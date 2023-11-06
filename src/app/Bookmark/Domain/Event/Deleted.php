<?php

namespace App\Bookmark\Domain\Event;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Zakladka zostala usunieta, nalezy usunac ja z bazy danych
 */
class Deleted implements Event
{
    private BookmarkId $bookmarkId;

    public function __construct(BookmarkId $bookmarkId)
    {
        $this->bookmarkId = $bookmarkId;
    }

    public function getBookmarkId(): BookmarkId
    {
        return $this->bookmarkId;
    }
}
