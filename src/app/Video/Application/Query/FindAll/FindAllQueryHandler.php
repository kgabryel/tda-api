<?php

namespace App\Video\Application\Query\FindAll;

use App\Video\Application\Query\VideoQueryHandler;

class FindAllQueryHandler extends VideoQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
