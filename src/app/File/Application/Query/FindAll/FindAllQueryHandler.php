<?php

namespace App\File\Application\Query\FindAll;

use App\File\Application\Query\FileQueryHandler;

class FindAllQueryHandler extends FileQueryHandler
{
    public function handle(FindAll $query): array
    {
        return $this->readRepository->findAll($this->userId);
    }
}
