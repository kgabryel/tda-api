<?php

namespace App\User\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Domain\Entity\User;

/**
 * Uzytkownik zglosil prosbe o kod sluzocy do potwierdzania e-maila. Nalezy wyslac wiadomosc z kodem
 */
class RequestedForActivationCode implements AsyncEvent
{
    private string $email;
    private AvailableLanguage $lang;
    private string $activationCode;

    public function __construct(string $email, AvailableLanguage $lang, string $activationCode)
    {
        $this->email = $email;
        $this->lang = $lang;
        $this->activationCode = $activationCode;
    }

    public static function fromUserData(User $user): self
    {
        return new self(
            $user->getNotificationEmail(),
            $user->getNotificationLanguage(),
            $user->getActivationCode()
        );
    }

    public function getActivationCode(): string
    {
        return $this->activationCode;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLang(): AvailableLanguage
    {
        return $this->lang;
    }
}
