<?php

namespace App\User\Application\Command\Register;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\User\Domain\Entity\AvailableLanguage;

/**
 * Rejestruje uzytkownika
 */
#[CommandHandler(RegisterHandler::class)]
class Register implements Command
{
    private string $email;
    private string $password;
    private AvailableLanguage $language;

    public function __construct(string $email, string $password, AvailableLanguage $language)
    {
        $this->email = $email;
        $this->password = $password;
        $this->language = $language;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLanguage(): AvailableLanguage
    {
        return $this->language;
    }
}
