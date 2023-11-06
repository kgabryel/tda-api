<?php

namespace App\Bookmark\Application\Command\UpdateBackgroundColor;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\ValueObject\Hex;

/**
 * Aktualizuje kolor tla zakladki
 */
#[CommandHandler(UpdateBackgroundColorHandler::class)]
class UpdateBackgroundColor extends ModifyBookmarkCommand
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
