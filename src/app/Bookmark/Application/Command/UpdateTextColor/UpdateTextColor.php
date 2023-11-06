<?php

namespace App\Bookmark\Application\Command\UpdateTextColor;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\ValueObject\Hex;

/**
 * Aktualizuje kolor tekstu zakladki
 */
#[CommandHandler(UpdateTextColorHandler::class)]
class UpdateTextColor extends ModifyBookmarkCommand
{
    private Hex $color;

    public function __construct(BookmarkId $id, Hex $color)
    {
        parent::__construct($id);
        $this->color = $color;
    }

    public function getColor(): Hex
    {
        return $this->color;
    }
}
