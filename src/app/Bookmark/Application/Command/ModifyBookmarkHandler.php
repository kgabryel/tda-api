<?php

namespace App\Bookmark\Application\Command;

use App\Bookmark\Application\Query\FindById\FindById;
use App\Bookmark\Domain\Entity\Bookmark;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\BookmarkId;

abstract class ModifyBookmarkHandler extends CommandHandler
{
    protected function getBookmark(BookmarkId $bookmarkId): Bookmark
    {
        return $this->queryBus->handle(new FindById($bookmarkId, QueryResult::DOMAIN_MODEL));
    }
}
