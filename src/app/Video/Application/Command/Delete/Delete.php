<?php

namespace App\Video\Application\Command\Delete;

use App\Core\Cqrs\CommandHandler;
use App\Video\Application\Command\ModifyVideoCommand;

/**
 * Usuwa film
 */
#[CommandHandler(DeleteHandler::class)]
class Delete extends ModifyVideoCommand
{
}
