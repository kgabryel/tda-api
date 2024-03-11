<?php

namespace App\User\Application\Command\SendConfirmEmail;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Wysyla e-maila z kodem sluzacym do potwierdzenia e-maila sluzacego do powiadomien
 */
#[CommandHandler(SendConfirmHandler::class)]
class SendConfirmEmail implements Command
{
}
