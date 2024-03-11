<?php

namespace App\Shared\Application\Query;

use App\Shared\Domain\Entity\UserId;

abstract class AssignedUserQueryHandler
{
    protected UserId $userId;

    public function setUserId(UserId $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
