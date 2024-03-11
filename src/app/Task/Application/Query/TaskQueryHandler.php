<?php

namespace App\Task\Application\Query;

use App\Shared\Application\Query\AssignedUserQueryHandler;
use App\Task\Application\ReadRepository;

abstract class TaskQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;

    public function __construct(ReadRepository $readRepository)
    {
        $this->readRepository = $readRepository;
    }
}
