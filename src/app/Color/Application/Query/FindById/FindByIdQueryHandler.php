<?php

namespace App\Color\Application\Query\FindById;

use App\Color\Application\Query\ColorQueryHandler;
use App\Color\Application\ViewModel\Color as ViewModel;
use App\Color\Domain\Entity\Color as DomainModel;
use App\Shared\Application\Query\QueryResult;

class FindByIdQueryHandler extends ColorQueryHandler
{
    public function handle(FindById $query): DomainModel|ViewModel
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->writeRepository->findById($query->getColorId(), $this->userId);
        }

        return $this->readRepository->findById($query->getColorId(), $this->userId);
    }
}
