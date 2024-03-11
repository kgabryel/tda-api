<?php

namespace App\Bookmark\Application\Command\UpdateIcon;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Aktualizuje ikone zakladki
 */
#[CommandHandler(UpdateIconHandler::class)]
class UpdateIcon extends ModifyBookmarkCommand
{
    private ?string $icon;

    public function __construct(BookmarkId $id, ?string $icon)
    {
        parent::__construct($id);
        $this->icon = $icon;
    }

    public function getValue(): ?string
    {
        return $this->icon;
    }
}
