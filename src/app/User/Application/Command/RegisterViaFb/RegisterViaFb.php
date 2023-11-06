<?php

namespace App\User\Application\Command\RegisterViaFb;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Domain\Entity\FacebookId;

/**
 * Rejestruje uzytkownika z wykorzystaniem Facebooka
 */
#[CommandHandler(RegisterViaFbHandler::class)]
class RegisterViaFb implements Command
{
    private FacebookId $facebookId;
    private AvailableLanguage $language;

    public function __construct(FacebookId $facebookId, AvailableLanguage $language)
    {
        $this->facebookId = $facebookId;
        $this->language = $language;
    }

    public function getFacebookId(): FacebookId
    {
        return $this->facebookId;
    }

    public function getLanguage(): AvailableLanguage
    {
        return $this->language;
    }
}
