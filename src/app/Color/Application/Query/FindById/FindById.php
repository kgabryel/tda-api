<?php

namespace App\Color\Application\Query\FindById;

use App\Color\Domain\Entity\ColorId;
use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;

/**
 * Pobiera kolor na podstawie przekazanego id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private ColorId $colorId;
    private QueryResult $result;

    public function __construct(ColorId $colorId, QueryResult $result)
    {
        $this->colorId = $colorId;
        $this->result = $result;
    }

    public function getColorId(): ColorId
    {
        return $this->colorId;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }
}
