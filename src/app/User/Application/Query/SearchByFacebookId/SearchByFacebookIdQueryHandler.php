<?php

namespace App\User\Application\Query\SearchByFacebookId;

use App\User\Application\Query\UserQueryHandler;
use App\User\Domain\Entity\User;

class SearchByFacebookIdQueryHandler extends UserQueryHandler
{
    public function handle(SearchByFacebookId $query): ?User
    {
        return $this->writeRepository->searchByFacebookId($query->getFacebookId());
    }
}
