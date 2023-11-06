<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\Command\PeriodicAlarm\CreateAlarmsForPeriodicAlarm\CreateAlarmsForPeriodicAlarm;
use App\Alarm\Application\Dto\NotificationsGroupsList;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Event\Port\AlarmsAdded;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\ListenEvent;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;

#[ListenEvent(AlarmsAdded::class)]
class AlarmsAddedHandler
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function handle(AlarmsAdded $event): void
    {
        /** @var PeriodicAlarm $periodicAlarm */
        $periodicAlarm = $this->queryBus->handle(
            new FindById($event->getAlarmGroupId(), QueryResult::DOMAIN_MODEL)
        );
        $notificationsGroupList = new NotificationsGroupsList();
        foreach ($periodicAlarm->getNotificationsGroups() as $group) {
            $notificationsGroupList->add($group);
        }
        $this->commandBus->handle(
            CreateAlarmsForPeriodicAlarm::fromPeriodicAlarm(
                $event->getDates(),
                $periodicAlarm,
                $event->getTaskGroup(),
                $notificationsGroupList
            )
        );
    }
}
