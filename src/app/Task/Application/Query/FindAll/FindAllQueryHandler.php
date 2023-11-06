<?php

namespace App\Task\Application\Query\FindAll;

use App\Task\Application\Query\TaskQueryHandler;

class FindAllQueryHandler extends TaskQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
