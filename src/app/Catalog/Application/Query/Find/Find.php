<?php

namespace App\Catalog\Application\Query\Find;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\BaseFind;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Pobiera liste katalogow na podstawie podanych id
 */
#[QueryHandler(FindQueryHandler::class)]
class Find extends BaseFind
{
    public function __construct(CatalogId ...$ids)
    {
        parent::__construct($ids);
    }
}
