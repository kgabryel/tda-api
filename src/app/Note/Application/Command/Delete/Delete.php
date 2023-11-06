<?php

namespace App\Note\Application\Command\Delete;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;

/**
 * Usuwa notatke
 */
#[CommandHandler(DeleteHandler::class)]
class Delete extends ModifyNoteCommand
{
}
