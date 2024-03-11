<?php

namespace App\User\Application\Query\GetSettings;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera ustawienia uzytkownika
 */
#[QueryHandler(GetSettingsQueryHandler::class)]
class GetSettings implements Query
{
}
