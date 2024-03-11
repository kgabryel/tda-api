<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\BookmarkId;

class BookmarksIdsList
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
        return array_map(static fn(BookmarkId $bookmarkId) => $bookmarkId->getValue(), $this->ids);
    }
}
