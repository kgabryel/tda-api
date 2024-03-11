<?php

namespace App\Video\Application\Query\Find;

use App\Video\Application\Query\VideoQueryHandler;

class FindQueryHandler extends VideoQueryHandler
{
    public function handle(Find $query): array
    {
        return $this->readRepository->find($this->userId, ...$query->getIds());
    }
}
