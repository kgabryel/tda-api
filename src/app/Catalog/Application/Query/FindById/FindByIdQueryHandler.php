<?php

namespace App\Catalog\Application\Query\FindById;

use App\Catalog\Application\Query\CatalogQueryHandler;
use App\Catalog\Application\ViewModel\Catalog as ViewModel;
use App\Catalog\Domain\Entity\Catalog as DomainModel;
use App\Shared\Application\Query\QueryResult;

class FindByIdQueryHandler extends CatalogQueryHandler
{
    public function handle(FindById $query): DomainModel|ViewModel
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->writeRepository->findById($query->getCatalogId(), $this->userId);
        }

        return $this->readRepository->findById($query->getCatalogId(), $this->userId);
    }
}
