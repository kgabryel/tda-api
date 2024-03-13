<?php

namespace App\Alarm\Application\EventHandler\PeriodicAlarm;

use App\Alarm\Application\Command\PeriodicAlarm\Activate\Activate;
use App\Alarm\Application\Command\PeriodicAlarm\Activate\ActivateAction;
use App\Alarm\Application\Command\PeriodicAlarm\Create\TaskGroup;
use App\Alarm\Application\Command\SingleAlarm\AddTask\AddTask;
use App\Alarm\Application\Command\SingleAlarm\Uncheck\Uncheck;
use App\Alarm\Application\Query\FindById\FindById;
use App\Alarm\Application\Service\AlarmService;
use App\Alarm\Application\Service\CreateData;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\PeriodicAlarm\AlarmsGroupsModified;
use App\Alarm\Domain\Event\PeriodicAlarm\Updated;
use App\Alarm\Domain\Event\Port\TriggeredAlarmActivationIntent;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\ListenEvent;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\DateService;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\TaskId;
use DateTimeImmutable;

#[ListenEvent(TriggeredAlarmActivationIntent::class)]
class TriggeredAlarmActivationIntentHandler
{
    private CommandBus $commandBus;
    private EventEmitter $eventEmitter;
    private QueryBus $queryBus;
    private array $alarmsInFuture;
    private AlarmService $alarmService;
    private SingleAlarmsIdsList $alarms;
    private DateTimeImmutable|false $currentDate;
    private PeriodicAlarm $alarm;

    public function __construct(
        CommandBus $commandBus,
        EventEmitter $eventEmitter,
        QueryBus $queryBus,
        AlarmService $alarmService
    ) {
        $this->commandBus = $commandBus;
        $this->eventEmitter = $eventEmitter;
        $this->queryBus = $queryBus;
        $this->alarmService = $alarmService;
    }

    protected function getPeriodicAlarm(AlarmsGroupId $alarmId): PeriodicAlarm
    {
        return $this->queryBus->handle(new FindById($alarmId, QueryResult::DOMAIN_MODEL));
    }

    public function handle(TriggeredAlarmActivationIntent $event): void
    {
        $action = ActivateAction::from($event->getAction());
        $this->alarm = $this->getPeriodicAlarm($event->getAlarmId());
        if (!$this->alarm->activate()) {
            return;
        }
        $this->eventEmitter->emit(new Updated($this->alarm));
        if ($action === ActivateAction::NOT_MODIFY) {
            $this->commandBus->handle(new Activate($event->getAlarmId(), $action));
        } else {
            $this->activate($event);
        }
        $this->eventEmitter->emit(new AlarmsGroupsModified($this->alarm->getUserId(), $this->alarm->getAlarmId()));
        $this->eventEmitter->emit(new AlarmsModified($this->alarm->getUserId(), ...$this->alarms->get()));
    }

    public function activate(TriggeredAlarmActivationIntent $event): bool
    {
        $this->alarms = new SingleAlarmsIdsList();
        $this->alarmsInFuture = $this->alarm->getAlarmsInFuture()->get();
        $dateService = DateService::get(new DateTimeImmutable(), DateService::getNextMonthEnd(), $this->alarm);
        $this->currentDate = DateService::toStartOfDay($dateService->getCurrent());
        while ($this->currentDate !== false) {
            /** @var TaskGroup $taskGroup */
            $taskGroup = $event->getTaskGroup()?->getByTime($this->currentDate->getTimestamp());
            $singleAlarm = $this->findAlarm($taskGroup->getAlarmId());
            $task = $taskGroup->getTaskId();
            if ($singleAlarm === null) {
                $this->create($this->alarm, $taskGroup);
            } else {
                $this->modify($singleAlarm, $task);
            }
            $dateService->setNext();
            $this->currentDate = DateService::toStartOfDay($dateService->getCurrent());
        }

        return true;
    }

    private function findAlarm(AlarmId $alarmId): ?SingleAlarm
    {
        /** @var SingleAlarm $alarm */
        foreach ($this->alarmsInFuture as $alarm) {
            if ($alarm->getAlarmId()->getValue() === $alarmId->getValue()) {
                return $alarm;
            }
        }

        return null;
    }

    private function create(PeriodicAlarm $alarm, TaskGroup $taskGroup): void
    {
        $id = $taskGroup->getAlarmId();
        $this->alarms->add($id);
        if ($this->currentDate !== false) {
            $this->alarmService->createForPeriodicAlarm(
                CreateData::createFromPeriodicAlarm($alarm, $this->currentDate, $id, $taskGroup->getTaskId())
            );
        }
    }

    private function modify(SingleAlarm $alarm, TaskId $taskId): void
    {
        $this->alarms->add($alarm->getAlarmId());
        if (!$alarm->hasTask()) {
            $this->commandBus->handle(new AddTask($alarm->getAlarmId(), $taskId));
        }
        $this->commandBus->handle(new Uncheck($alarm->getAlarmId()));
    }
}
