<?php

namespace App\User\Domain\Event;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\UserId;

/**
 * E-mail sluzacy do powiadomien zostal potwierdzony, nalezy zaktualizowac to w bazie danych
 */
class EmailConfirmed implements Event
{
    private UserId $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
