<?php

namespace App\Alarm\Application\Query\FindByNotificationsGroupId;

use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\WriteRepository;
use App\Shared\Application\Query\AssignedUserQueryHandler;

class FindByNotificationsGroupIdQueryHandler extends AssignedUserQueryHandler
{
    private WriteRepository $writeRepository;

    public function __construct(WriteRepository $writeRepository)
    {
        $this->writeRepository = $writeRepository;
    }

    public function handle(FindByNotificationsGroupId $query): PeriodicAlarm
    {
        return $this->writeRepository->findByNotificationsGroupId($this->userId, $query->getNotificationId());
    }
}
