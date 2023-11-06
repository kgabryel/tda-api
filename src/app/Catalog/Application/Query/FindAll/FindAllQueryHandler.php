<?php

namespace App\Catalog\Application\Query\FindAll;

use App\Catalog\Application\Query\CatalogQueryHandler;

class FindAllQueryHandler extends CatalogQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
