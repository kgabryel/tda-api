<?php

namespace App\Bookmark\Application\Command\UpdateHref;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Aktualizuje adres zakladki oraz ikone - zalezne od flagi
 */
#[CommandHandler(UpdateHrefHandler::class)]
class UpdateHref extends ModifyBookmarkCommand
{
    private string $href;
    private bool $updateIcon;

    public function __construct(BookmarkId $id, string $href, bool $updateIcon = false)
    {
        parent::__construct($id);
        $this->href = $href;
        $this->updateIcon = $updateIcon;
    }

    public function getValue(): string
    {
        return $this->href;
    }

    public function updateIcon(): bool
    {
        return $this->updateIcon;
    }
}
