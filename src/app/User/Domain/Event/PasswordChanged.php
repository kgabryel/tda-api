<?php

namespace App\User\Domain\Event;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\UserId;

/**
 * Haslo zostalo zmienione, nalezy zaktualizowac je w bazie danych
 */
class PasswordChanged implements Event
{
    private UserId $userId;
    private string $password;

    public function __construct(UserId $userId, string $password)
    {
        $this->userId = $userId;
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
