<?php

namespace App\Alarm\Application\Query;

use App\Alarm\Application\ReadRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

abstract class AlarmQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;

    public function __construct(ReadRepository $readRepository)
    {
        $this->readRepository = $readRepository;
    }
}
