<?php

namespace App\Note\Application\Query;

use App\Note\Application\ReadRepository;
use App\Note\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

abstract class NoteQueryHandler extends AssignedUserQueryHandler
{
    protected ReadRepository $readRepository;
    protected WriteRepository $writeRepository;

    public function __construct(ReadRepository $readRepository, WriteRepository $writeRepository)
    {
        $this->readRepository = $readRepository;
        $this->writeRepository = $writeRepository;
    }
}
