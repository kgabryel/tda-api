<?php

namespace App\Bookmark\Application\Command;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\BookmarkId;

abstract class ModifyBookmarkCommand implements Command
{
    protected BookmarkId $bookmarkId;

    public function __construct(BookmarkId $bookmarkId)
    {
        $this->bookmarkId = $bookmarkId;
    }

    public function getBookmarkId(): BookmarkId
    {
        return $this->bookmarkId;
    }
}
