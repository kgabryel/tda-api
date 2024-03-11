<?php

namespace App\Bookmark\Application\Query\FindById;

use App\Bookmark\Application\Query\BookmarkQueryHandler;
use App\Bookmark\Application\ViewModel\Bookmark as ViewModel;
use App\Bookmark\Domain\Entity\Bookmark as DomainModel;
use App\Shared\Application\Query\QueryResult;

class FindByIdQueryHandler extends BookmarkQueryHandler
{
    public function handle(FindById $query): DomainModel|ViewModel
    {
        if ($query->getResult() === QueryResult::DOMAIN_MODEL) {
            return $this->writeRepository->findById($query->getBookmarkId(), $this->userId);
        }

        return $this->readRepository->findById($query->getBookmarkId(), $this->userId);
    }
}
