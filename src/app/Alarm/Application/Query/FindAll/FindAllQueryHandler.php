<?php

namespace App\Alarm\Application\Query\FindAll;

use App\Alarm\Application\Query\AlarmQueryHandler;

class FindAllQueryHandler extends AlarmQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
