<?php

namespace App\Note\Application\Query\FindAll;

use App\Note\Application\Query\NoteQueryHandler;

class FindAllQueryHandler extends NoteQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
