<?php

namespace App\Note\Application\Query\Find;

use App\Note\Application\Query\NoteQueryHandler;

class FindQueryHandler extends NoteQueryHandler
{
    public function handle(Find $query): array
    {
        return $this->readRepository->find($this->userId, ...$query->getIds());
    }
}
