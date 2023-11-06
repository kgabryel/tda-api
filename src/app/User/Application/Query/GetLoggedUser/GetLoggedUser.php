<?php

namespace App\User\Application\Query\GetLoggedUser;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera zalogowanego uzytkownika
 */
#[QueryHandler(GetLoggedUserQueryHandler::class)]
class GetLoggedUser implements Query
{
}
