<?php

namespace App\User\Application\Command\ChangeEmail;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Aktualizuje lub usuwa e-mail uzytkownika uzywany do powiadomien
 */
#[CommandHandler(ChangeEmailHandler::class)]
class ChangeEmail implements Command
{
    private ?string $email;

    public function __construct(?string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
