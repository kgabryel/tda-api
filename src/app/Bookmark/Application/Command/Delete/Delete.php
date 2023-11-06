<?php

namespace App\Bookmark\Application\Command\Delete;

use App\Bookmark\Application\Command\ModifyBookmarkCommand;
use App\Core\Cqrs\CommandHandler;

/**
 * Usuwa zakladke
 */
#[CommandHandler(DeleteHandler::class)]
class Delete extends ModifyBookmarkCommand
{
}
