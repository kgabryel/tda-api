<?php

namespace App\Catalog\Application;

use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

interface Notificator
{
    public function catalogsChanges(UserId|int $user, CatalogId ...$ids): void;
}
