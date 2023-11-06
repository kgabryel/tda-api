<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\CreateAlarmsForPeriodicAlarm;

use App\Alarm\Application\Service\AlarmService;
use App\Alarm\Application\Service\CreateData;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\AlarmId;

class CreateAlarmsForPeriodicAlarmHandler extends CommandHandler
{
    private UuidInterface $uuid;
    private AlarmService $alarmService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        UuidInterface $uuid,
        AlarmService $alarmService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->uuid = $uuid;
        $this->alarmService = $alarmService;
    }

    public function handle(CreateAlarmsForPeriodicAlarm $command): void
    {
        $alarms = new SingleAlarmsIdsList();
        $dates = $command->getDates()->get();
        foreach ($dates as $date) {
            $task = null;
            if ($command->getTaskGroup() === null) {
                $id = new AlarmId($this->uuid->getValue());
            } else {
                $taskGroup = $command->getTaskGroup()->getByTime($date->getTimestamp());
                $id = $taskGroup->getAlarmId();
                $task = $taskGroup->getTaskId();
            }

            $alarms->add($id);
            $this->alarmService->createForPeriodicAlarm(CreateData::createFromCommand($command, $date, $id, $task));
        }
        $this->eventEmitter->emit(new AlarmsModified($command->getUserId(), ...$alarms->get()));
    }
}
