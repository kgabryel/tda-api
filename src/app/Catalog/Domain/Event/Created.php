<?php

namespace App\Catalog\Domain\Event;

use App\Catalog\Domain\Entity\Catalog;
use App\Core\Cqrs\AsyncEvent;

/**
 * Katalog zostaly dodany, nalezy zmodyfikowac powiazane elementy
 */
class Created implements AsyncEvent
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
