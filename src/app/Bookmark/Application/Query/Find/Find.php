<?php

namespace App\Bookmark\Application\Query\Find;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\BaseFind;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Pobiera liste zakladek na podstawie podanych id
 */
#[QueryHandler(FindQueryHandler::class)]
class Find extends BaseFind
{
    public function __construct(BookmarkId ...$ids)
    {
        parent::__construct($ids);
    }
}
