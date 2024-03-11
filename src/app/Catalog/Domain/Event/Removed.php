<?php

namespace App\Catalog\Domain\Event;

use App\Catalog\Domain\Entity\Catalog;
use App\Core\Cqrs\Event;

/**
 * Katalog zostaly usuniety, nalezy zmodyfikowac powiazane elementy
 */
class Removed implements Event
{
    private Catalog $catalog;

    public function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
    }

    public function getCatalog(): Catalog
    {
        return $this->catalog;
    }
}
