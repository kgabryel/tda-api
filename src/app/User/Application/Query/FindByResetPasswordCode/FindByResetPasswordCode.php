<?php

namespace App\User\Application\Query\FindByResetPasswordCode;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;

/**
 * Pobiera uzytkownika na podstawie kodu do resetowania hasla
 */
#[QueryHandler(FindByResetPasswordCodeQueryHandler::class)]
class FindByResetPasswordCode implements Query
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
