<?php

namespace App\User\Application\Command\RequestForResetPassword;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\User\Domain\Entity\AvailableLanguage;

/**
 * Wysyla e-maila z mozliwoscia resetu hasla
 */
#[CommandHandler(RequestForResetPasswordHandler::class)]
class RequestForResetPassword implements Command
{
    private string $email;
    private AvailableLanguage $language;

    public function __construct(string $email, AvailableLanguage $language)
    {
        $this->email = $email;
        $this->language = $language;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLanguage(): AvailableLanguage
    {
        return $this->language;
    }
}
