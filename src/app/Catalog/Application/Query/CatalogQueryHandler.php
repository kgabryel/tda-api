<?php

namespace App\Catalog\Application\Query;

use App\Catalog\Application\ReadRepository;
use App\Catalog\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

abstract class CatalogQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;
    protected WriteRepository $writeRepository;

    public function __construct(ReadRepository $readRepository, WriteRepository $writeRepository)
    {
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }
}
