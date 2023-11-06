<?php

namespace App\File\Application\Command\Delete;

use App\Core\Cqrs\CommandHandler;
use App\File\Application\Command\ModifyFileCommand;

/**
 * Usuwa plik
 */
#[CommandHandler(DeleteHandler::class)]
class Delete extends ModifyFileCommand
{
}
