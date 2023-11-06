<?php

namespace App\File\Application\Query\Find;

use App\File\Application\Query\FileQueryHandler;

class FindQueryHandler extends FileQueryHandler
{
    public function handle(Find $query): array
    {
        return $this->readRepository->find($this->userId, ...$query->getIds());
    }
}
