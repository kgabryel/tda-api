<?php

namespace App\Catalog\Application\EventHandler;

use App\Catalog\Application\CatalogManagerInterface;

abstract class EventHandler
{
    protected CatalogManagerInterface $catalogManager;

    public function __construct(CatalogManagerInterface $catalogManager)
    {
        $this->catalogManager = $catalogManager;
    }
}
