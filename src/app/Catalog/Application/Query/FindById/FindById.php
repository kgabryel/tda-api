<?php

namespace App\Catalog\Application\Query\FindById;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Pobiera katalog na podstawie id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private CatalogId $catalogId;
    private QueryResult $result;

    public function __construct(CatalogId $catalogId, QueryResult $result)
    {
        $this->catalogId = $catalogId;
        $this->result = $result;
    }

    public function getCatalogId(): CatalogId
    {
        return $this->catalogId;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }
}
