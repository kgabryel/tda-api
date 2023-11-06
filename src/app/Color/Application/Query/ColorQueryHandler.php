<?php

namespace App\Color\Application\Query;

use App\Color\Application\ReadRepository;
use App\Color\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

abstract class ColorQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;
    protected WriteRepository $writeRepository;

    public function __construct(ReadRepository $readRepository, WriteRepository $writeRepository)
    {
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }
}
