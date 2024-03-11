<?php

namespace App\Catalog\Domain;

use App\Catalog\Domain\Entity\Catalog;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

interface WriteRepository
{
    public function findById(CatalogId $catalogId, UserId $userId): Catalog;
}
