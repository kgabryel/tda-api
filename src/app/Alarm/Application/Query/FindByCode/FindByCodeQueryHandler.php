<?php

namespace App\Alarm\Application\Query\FindByCode;

use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

class FindByCodeQueryHandler extends AssignedUserQueryHandler
{
    private WriteRepository $writeRepository;

    public function __construct(WriteRepository $writeRepository)
    {
        $this->writeRepository = $writeRepository;
    }

    public function handle(FindByCode $query): SingleAlarm
    {
        return $this->writeRepository->findByDeactivationCode($this->userId, $query->getCode());
    }
}
