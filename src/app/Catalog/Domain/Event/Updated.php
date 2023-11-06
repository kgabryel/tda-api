<?php

namespace App\Catalog\Domain\Event;

use App\Catalog\Domain\Entity\Catalog;
use App\Core\Cqrs\Event;

/**
 * Katalog zostal zmodyfikowany, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
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
