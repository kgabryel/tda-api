<?php

namespace App\Bookmark\Application\Query\Find;

use App\Bookmark\Application\Query\BookmarkQueryHandler;

class FindQueryHandler extends BookmarkQueryHandler
{
    public function handle(Find $query): array
    {
        return $this->readRepository->find($this->userId, ...$query->getIds());
    }
}
