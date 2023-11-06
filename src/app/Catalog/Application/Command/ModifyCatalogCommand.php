<?php

namespace App\Catalog\Application\Command;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\CatalogId;

abstract class ModifyCatalogCommand implements Command
{
    protected CatalogId $catalogId;

    public function __construct(CatalogId $catalogId)
    {
        $this->catalogId = $catalogId;
    }

    public function getCatalogId(): CatalogId
    {
        return $this->catalogId;
    }
}
