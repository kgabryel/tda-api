<?php

namespace App\User\Application\Query\GetSettings;

use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\AssignedUserQueryHandler;
use App\User\Application\Query\GetEmailState\GetEmailState;
use App\User\Application\ReadRepository;
use App\User\Application\ViewModel\Settings;

class GetSettingsQueryHandler extends AssignedUserQueryHandler
{
    private QueryBus $queryBus;
    private ReadRepository $readRepository;

    public function __construct(QueryBus $queryBus, ReadRepository $readRepository)
    {
        $this->queryBus = $queryBus;
        $this->readRepository = $readRepository;
    }

    public function handle(GetSettings $query): Settings
    {
        $emailState = $this->queryBus->handle(new GetEmailState());

        return $this->readRepository->getSettings($this->userId)->setEmailState($emailState);
    }
}
