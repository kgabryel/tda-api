<?php

namespace App\Color\Application\Query\FindAll;

use App\Color\Application\Query\ColorQueryHandler;

class FindAllQueryHandler extends ColorQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
