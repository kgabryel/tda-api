<?php

namespace App\Video\Application\Query;

use App\Shared\Application\Query\AssignedUserQueryHandler;
use App\Video\Application\ReadRepository;
use App\Video\Domain\WriteRepository;

abstract class VideoQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;
    protected WriteRepository $writeRepository;

    public function __construct(ReadRepository $readRepository, WriteRepository $writeRepository)
    {
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }
}
