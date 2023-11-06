<?php

namespace App\Core;

use App\Shared\Domain\Entity\UserId;
use Illuminate\Support\Facades\Auth;

class BusUtils
{
    private ?UserId $userId;

    public function __construct()
    {
        $this->userId = null;
    }

    public function createObject(string $name): object
    {
        return resolve($name);
    }

    public function getUserId(): UserId
    {
        return $this->userId ?? new UserId(Auth::user()?->getId() ?? 0);
    }

    public function setUserId(UserId $userId): void
    {
        $this->userId = $userId;
    }
}
