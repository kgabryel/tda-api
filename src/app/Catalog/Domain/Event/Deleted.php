<?php

namespace App\Catalog\Domain\Event;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Katalog zostal usuniety, nalezy usunac go z bazy danych
 */
class Deleted implements Event
{
    private CatalogId $catalogId;

    public function __construct(CatalogId $catalogId)
    {
        $this->catalogId = $catalogId;
    }

    public function getCatalogId(): CatalogId
    {
        return $this->catalogId;
    }
}
