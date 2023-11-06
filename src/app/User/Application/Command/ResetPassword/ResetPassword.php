<?php

namespace App\User\Application\Command\ResetPassword;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Resetuje haslo uzytkownika
 */
#[CommandHandler(ResetPasswordHandler::class)]
class ResetPassword implements Command
{
    private string $code;
    private string $password;

    public function __construct(string $code, string $password)
    {
        $this->code = $code;
        $this->password = $password;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
