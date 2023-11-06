<?php

namespace App\Video\Application\Query\FindById;

use App\Shared\Application\Query\QueryResult;
use App\Video\Application\Query\VideoQueryHandler;
use App\Video\Application\ViewModel\Video as ViewModel;
use App\Video\Domain\Entity\Video as DomainModel;

class FindByIdQueryHandler extends VideoQueryHandler
{
    public function handle(FindById $query): DomainModel|ViewModel
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->writeRepository->findById($query->getVideoId(), $this->userId);
        }

        return $this->readRepository->findById($query->getVideoId(), $this->userId);
    }
}
