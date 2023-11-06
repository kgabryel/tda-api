<?php

namespace App\Bookmark\Domain\Event;

use App\Catalog\Domain\Event\CatalogsModified;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane katalogi zostaly zmodyfikowane, zostaly odpiete lub przypiete do zakladki
 */
class CatalogsAssigmentChanged implements CatalogsModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, CatalogId ...$ids)
    {
        $this->userId = $userId;
        $this->ids = $ids;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
