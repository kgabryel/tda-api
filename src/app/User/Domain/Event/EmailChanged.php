<?php

namespace App\User\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Domain\Entity\User;

/**
 * E-mail sluzacy do powiadomien zostal zmodyfikowany lub usuniety, nalezy zaktualizowac go w bazie danych
 */
class EmailChanged implements AsyncEvent
{
    private UserId $userId;
    private ?string $email;
    private AvailableLanguage $lang;
    private string $activationCode;

    public function __construct(UserId $userId, ?string $email, AvailableLanguage $lang, string $activationCode)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->lang = $lang;
        $this->activationCode = $activationCode;
    }

    public static function fromUserData(User $user): self
    {
        return new self(
            $user->getUserId(),
            $user->getNotificationEmail(),
            $user->getNotificationLanguage(),
            $user->getActivationCode()
        );
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getActivationCode(): string
    {
        return $this->activationCode;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getLang(): AvailableLanguage
    {
        return $this->lang;
    }
}
