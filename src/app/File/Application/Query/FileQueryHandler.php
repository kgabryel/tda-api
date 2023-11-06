<?php

namespace App\File\Application\Query;

use App\File\Application\ReadRepository;
use App\File\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

abstract class FileQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;
    protected WriteRepository $writeRepository;

    public function __construct(ReadRepository $readRepository, WriteRepository $writeRepository)
    {
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }
}
