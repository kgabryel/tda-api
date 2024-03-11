<?php

namespace App\Note\Application\Query\FindById;

use App\Note\Application\Query\NoteQueryHandler;
use App\Note\Application\ViewModel\Note as ViewModel;
use App\Note\Domain\Entity\Note as DomainModel;
use App\Shared\Application\Query\QueryResult;

class FindByIdQueryHandler extends NoteQueryHandler
{
    public function handle(FindById $query): DomainModel|ViewModel
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->writeRepository->findById($query->getNoteId(), $this->userId);
        }

        return $this->readRepository->findById($query->getNoteId(), $this->userId);
    }
}
