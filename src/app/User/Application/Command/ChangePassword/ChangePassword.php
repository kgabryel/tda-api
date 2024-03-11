<?php

namespace App\User\Application\Command\ChangePassword;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Zmienia haslo uzytkownika
 */
#[CommandHandler(ChangePasswordHandler::class)]
class ChangePassword implements Command
{
    private string $newPassword;
    private string $oldPassword;

    public function __construct(string $oldPassword, string $newPassword)
    {
        $this->newPassword = $newPassword;
        $this->oldPassword = $oldPassword;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }
}
