<?php

namespace App\Task\Application\Dto;

use App\Shared\Domain\Entity\BookmarkId;

class BookmarksList
{
    private array $ids;

    public function __construct(BookmarkId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(BookmarkId $catalogId) => $catalogId->getValue(), $this->ids);
    }
}
