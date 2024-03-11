<?php

namespace App\Shared\Application\Command;

use App\Shared\Domain\Entity\UserId;

abstract class AssignedUserCommandHandler extends CommandHandler
{
    protected UserId $userId;

    public function setUserId(UserId $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
