<?php

namespace App\User\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\User\Domain\Entity\AvailableLanguage;

/**
 * Uzytkownik zglosil prosbe o zresetowanie hasla. Nalezy wyslac wiadomosc z kodem
 */
class RequestedForPasswordReset implements AsyncEvent
{
    private string $email;
    private AvailableLanguage $language;
    private string $code;

    public function __construct(string $email, AvailableLanguage $language, string $code)
    {
        $this->email = $email;
        $this->language = $language;
        $this->code = $code;
    }

    public function getLanguage(): AvailableLanguage
    {
        return $this->language;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
