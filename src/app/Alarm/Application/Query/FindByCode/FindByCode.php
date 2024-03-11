<?php

namespace App\Alarm\Application\Query\FindByCode;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera alarm na podstawie kodu dezaktywacyjnego
 */
#[QueryHandler(FindByCodeQueryHandler::class)]
class FindByCode implements Query
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
