<?php

namespace App\User\Application\Query\GetLoggedUser;

use App\User\Application\Query\UserQueryHandler;
use App\User\Domain\Entity\User;

class GetLoggedUserQueryHandler extends UserQueryHandler
{
    public function handle(): User
    {
        return $this->writeRepository->getLoggedUser();
    }
}
