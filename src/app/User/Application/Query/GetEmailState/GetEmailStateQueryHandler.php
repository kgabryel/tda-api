<?php

namespace App\User\Application\Query\GetEmailState;

use App\Shared\Application\Query\AssignedUserQueryHandler;
use App\User\Application\ReadRepository;
use App\User\Application\ViewModel\EmailState;

class GetEmailStateQueryHandler extends AssignedUserQueryHandler
{
    private ReadRepository $readRepository;

    public function __construct(ReadRepository $readRepository)
    {
        $this->readRepository = $readRepository;
    }

    public function handle(GetEmailState $query): EmailState
    {
        return $this->readRepository->getEmailState($this->userId);
    }
}
