<?php

namespace App\User\Application\Query;

use App\User\Application\ReadRepository;
use App\User\Domain\WriteRepository;

abstract class UserQueryHandler
{
    protected ReadRepository $readRepository;
    protected WriteRepository $writeRepository;

    public function __construct(ReadRepository $readRepository, WriteRepository $writeRepository)
    {
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }
}
