<?php

namespace App\Bookmark\Application\Query\FindAll;

use App\Bookmark\Application\Query\BookmarkQueryHandler;

class FindAllQueryHandler extends BookmarkQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
