<?php

namespace App\User\Application\Query\SearchByFacebookId;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\User\Domain\Entity\FacebookId;

/**
 * Pobiera uzytkownika na podstawie id facebooka
 */
#[QueryHandler(SearchByFacebookIdQueryHandler::class)]
class SearchByFacebookId implements Query
{
    private FacebookId $facebookId;

    public function __construct(FacebookId $facebookId)
    {
        $this->facebookId = $facebookId;
    }

    public function getFacebookId(): FacebookId
    {
        return $this->facebookId;
    }
}
