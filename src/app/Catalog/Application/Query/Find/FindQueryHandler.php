<?php

namespace App\Catalog\Application\Query\Find;

use App\Catalog\Application\Query\CatalogQueryHandler;

class FindQueryHandler extends CatalogQueryHandler
{
    public function handle(Find $query): array
    {
        return $this->readRepository->find($this->userId, ...$query->getIds());
    }
}
