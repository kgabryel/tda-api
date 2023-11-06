<?php

namespace App\Alarm\Application\Query\FindByNotificationId;

use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

class FindByNotificationIdQueryHandler extends AssignedUserQueryHandler
{
    private WriteRepository $writeRepository;

    public function __construct(WriteRepository $writeRepository)
    {
        $this->writeRepository = $writeRepository;
    }

    public function handle(FindByNotificationId $query): SingleAlarm
    {
        return $this->writeRepository->findByNotificationId($this->userId, $query->getNotificationId());
    }
}
