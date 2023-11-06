<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\CatalogId;

class CatalogsIdsList
{
    private array $ids;

    public function __construct(CatalogId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(CatalogId $catalogId) => $catalogId->getValue(), $this->ids);
    }
}
