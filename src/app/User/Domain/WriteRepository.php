<?php

namespace App\User\Domain;

use App\Shared\Domain\Entity\UserId;
use App\User\Domain\Entity\FacebookId;
use App\User\Domain\Entity\User;

interface WriteRepository
{
    public function getLoggedUser(): User;

    public function findById(UserId $userId): User;

    public function searchByFacebookId(FacebookId $facebookId): ?User;

    public function findByResetPasswordCode(string $code): User;
}
