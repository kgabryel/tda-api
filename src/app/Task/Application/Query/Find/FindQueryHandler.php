<?php

namespace App\Task\Application\Query\Find;

use App\Task\Application\Query\TaskQueryHandler;

class FindQueryHandler extends TaskQueryHandler
{
    public function handle(Find $query): array
    {
        return $this->readRepository->find($this->userId, ...$query->getIds());
    }
}
