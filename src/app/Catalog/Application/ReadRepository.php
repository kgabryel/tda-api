<?php

namespace App\Catalog\Application;

use App\Catalog\Application\ViewModel\Catalog;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

interface ReadRepository
{
    public function findById(CatalogId $catalogId, UserId $userId): Catalog;

    public function find(UserId $userId, CatalogId ...$catalogsIds): array;

    public function findAll(UserId $userId): array;
}
