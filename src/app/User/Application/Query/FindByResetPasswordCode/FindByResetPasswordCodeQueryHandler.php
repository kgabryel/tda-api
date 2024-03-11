<?php

namespace App\User\Application\Query\FindByResetPasswordCode;

use App\User\Application\Query\UserQueryHandler;
use App\User\Domain\Entity\User;

class FindByResetPasswordCodeQueryHandler extends UserQueryHandler
{
    public function handle(FindByResetPasswordCode $query): User
    {
        return $this->writeRepository->findByResetPasswordCode($query->getCode());
    }
}
