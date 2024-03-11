<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Activate;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\Uncheck\Uncheck;
use App\Alarm\Application\Service\AlarmService;
use App\Alarm\Application\Service\CreateData;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\PeriodicAlarm\Updated;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\DateService;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use DateTimeImmutable;

class ActivateHandler extends ModifyAlarmHandler
{
    private array $alarmsInFuture;
    private AlarmService $alarmService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        CommandBus $commandBus,
        AlarmService $alarmService
    ) {
        parent::__construct($queryBus, $eventEmitter, $commandBus);
        $this->alarmService = $alarmService;
    }

    public function handle(Activate $command): bool
    {
        $alarm = $this->getPeriodicAlarm($command->getAlarmId());
        if (!$alarm->activate()) {
            return false;
        }
        $this->eventEmitter->emit(new Updated($alarm));
        if ($command->getAction() === ActivateAction::NOT_MODIFY) {
            return true;
        }
        $alarms = new SingleAlarmsIdsList();
        $this->alarmsInFuture = $alarm->getAlarmsInFuture()->get();
        $dateService = DateService::get(new DateTimeImmutable(), DateService::getNextMonthEnd(), $alarm);
        $currentDate = DateService::toStartOfDay($dateService->getCurrent());
        while ($currentDate !== false) {
            $singleAlarm = $this->findAlarm($currentDate);
            if ($singleAlarm === null) {
                $id = $this->alarmService->createForPeriodicAlarm(
                    CreateData::createFromPeriodicAlarm($alarm, $currentDate)
                );
                $alarms->add($id);
            } else {
                $this->commandBus->handle(new Uncheck($singleAlarm->getAlarmId()));
            }
            $dateService->setNext();
            $currentDate = DateService::toStartOfDay($dateService->getCurrent());
        }
        $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), ...$alarms->get()));

        return true;
    }

    private function findAlarm(DateTimeImmutable $date): ?SingleAlarm
    {
        /** @var SingleAlarm $alarm */
        foreach ($this->alarmsInFuture as $alarm) {
            if ($alarm->getDate()?->getTimestamp() === $date->getTimestamp()) {
                return $alarm;
            }
        }

        return null;
    }
}
