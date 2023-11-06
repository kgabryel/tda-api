<?php

namespace App\Bookmark\Application\Query;

use App\Bookmark\Application\ReadRepository;
use App\Bookmark\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

abstract class BookmarkQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;
    protected WriteRepository $writeRepository;

    public function __construct(ReadRepository $readRepository, WriteRepository $writeRepository)
    {
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }
}
