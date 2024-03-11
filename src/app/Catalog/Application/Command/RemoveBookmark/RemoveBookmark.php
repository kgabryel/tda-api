<?php

namespace App\Catalog\Application\Command\RemoveBookmark;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Odpina zaklade od katalogu
 */
#[CommandHandler(RemoveBookmarkHandler::class)]
class RemoveBookmark extends ModifyCatalogCommand
{
    private BookmarkId $bookmarkId;

    public function __construct(CatalogId $id, BookmarkId $bookmarkId)
    {
        parent::__construct($id);
        $this->bookmarkId = $bookmarkId;
    }

    public function getBookmarkId(): BookmarkId
    {
        return $this->bookmarkId;
    }
}
