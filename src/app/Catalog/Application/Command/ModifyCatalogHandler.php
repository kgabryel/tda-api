<?php

namespace App\Catalog\Application\Command;

use App\Catalog\Application\Query\FindById\FindById;
use App\Catalog\Domain\Entity\Catalog;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\CatalogId;

abstract class ModifyCatalogHandler extends CommandHandler
{
    protected function getCatalog(CatalogId $id): Catalog
    {
        return $this->queryBus->handle(new FindById($id, QueryResult::DOMAIN_MODEL));
    }
}
