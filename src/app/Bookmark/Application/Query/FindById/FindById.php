<?php

namespace App\Bookmark\Application\Query\FindById;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Pobiera zakladke na podstawie id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private BookmarkId $bookmarkId;
    private QueryResult $result;

    public function __construct(BookmarkId $bookmarkId, QueryResult $result)
    {
        $this->bookmarkId = $bookmarkId;
        $this->result = $result;
    }

    public function getBookmarkId(): BookmarkId
    {
        return $this->bookmarkId;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }
}
