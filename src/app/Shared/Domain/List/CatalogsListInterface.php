<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\SyncResult;

interface CatalogsListInterface
{
    public function getIds(): array;

    public function sync(CatalogId ...$ids): SyncResult;

    public function detach(CatalogId $id): bool;

    public function attach(CatalogId $id): bool;
}
