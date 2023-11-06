<?php

namespace App\Bookmark\Application\Command\UpdateCatalogs;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Aktualizuje liste katalogow przypisanych do zakladki
 */
#[CommandHandler(UpdateCatalogsHandler::class)]
class UpdateCatalogs extends ModifyBookmarkCommand
{
    private array $catalogs;

    public function __construct(BookmarkId $id, CatalogId ...$catalogs)
    {
        parent::__construct($id);
        $this->catalogs = $catalogs;
    }

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }
}
