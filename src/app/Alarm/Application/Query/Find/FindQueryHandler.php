<?php

namespace App\Alarm\Application\Query\Find;

use App\Alarm\Application\Query\AlarmQueryHandler;

class FindQueryHandler extends AlarmQueryHandler
{
    public function handle(Find $query): array
    {
        return $this->readRepository->find($this->userId, ...$query->getIds());
    }
}
