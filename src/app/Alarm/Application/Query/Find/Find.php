<?php

namespace App\Alarm\Application\Query\Find;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\BaseFind;

/**
 * Pobiera liste alarmow na podstawie podanych id
 */
#[QueryHandler(FindQueryHandler::class)]
class Find extends BaseFind
{
    public function __construct(string ...$ids)
    {
        parent::__construct($ids);
    }
}
