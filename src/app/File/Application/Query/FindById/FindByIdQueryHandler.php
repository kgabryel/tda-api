<?php

namespace App\File\Application\Query\FindById;

use App\File\Application\Query\FileQueryHandler;
use App\File\Application\ViewModel\File as ViewModel;
use App\File\Domain\Entity\File as DomainModel;
use App\Shared\Application\Query\QueryResult;

class FindByIdQueryHandler extends FileQueryHandler
{
    public function handle(FindById $query): DomainModel|ViewModel
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->writeRepository->findById($query->getFileId(), $this->userId);
        }

        return $this->readRepository->findById($query->getFileId(), $this->userId);
    }
}
