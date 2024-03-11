<?php

namespace App\Catalog\Application\Command\UpdateBookmarks;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Aktualizuje liste zakladek przypisanych do katalogu
 */
#[CommandHandler(UpdateBookmarksHandler::class)]
class UpdateBookmarks extends ModifyCatalogCommand
{
    private array $bookmarks;

    public function __construct(CatalogId $id, BookmarkId ...$bookmarks)
    {
        parent::__construct($id);
        $this->bookmarks = $bookmarks;
    }

    public function getBookmarks(): array
    {
        return $this->bookmarks;
    }
}
