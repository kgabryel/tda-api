<?php

namespace App\User\Application\Query\GetEmailState;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera stan e-maila, czy zostal podany, a jezeli tak to czy zostal potwierdzony
 */
#[QueryHandler(GetEmailStateQueryHandler::class)]
class GetEmailState implements Query
{
}
