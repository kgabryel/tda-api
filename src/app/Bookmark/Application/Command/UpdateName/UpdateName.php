<?php

namespace App\Bookmark\Application\Command\UpdateName;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Aktualizuje nazwe zakladki
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifyBookmarkCommand
{
    private string $name;

    public function __construct(BookmarkId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->name;
    }
}
