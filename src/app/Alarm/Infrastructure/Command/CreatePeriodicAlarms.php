<?php

namespace App\Alarm\Infrastructure\Command;

use App\Alarm\Application\Command\PeriodicAlarm\CreateAlarmsForPeriodicAlarm\CreateAlarmsForPeriodicAlarm;
use App\Alarm\Application\Dto\NotificationsGroupsList;
use App\Alarm\Application\Query\FindAlarmsToCreate\FindAlarmsToCreate;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Core\BusUtils;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\DateService;
use App\Shared\Application\UuidInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreatePeriodicAlarms extends Command
{
    protected $signature = 'alarms:periodic-create';

    private CommandBus $commandBus;
    private QueryBus $queryBus;
    private UuidInterface $uuid;
    private BusUtils $busUtils;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus, UuidInterface $uuid, BusUtils $busUtils)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->uuid = $uuid;
        $this->busUtils = $busUtils;
    }

    public function handle(): int
    {
        $cronId = $this->uuid->getValue();
        $start = DateService::getNextNthMonthStart(2);
        $alarms = $this->queryBus->handle(new FindAlarmsToCreate(Carbon::instance($start)->toDateTimeImmutable()));
        Log::info('alarms:periodic-create', [
            'id' => $cronId,
            'alarms' => array_map(static fn(PeriodicAlarm $alarm) => $alarm->getAlarmId(), $alarms)
        ]);
        /** @var PeriodicAlarm $alarmGroup */
        foreach ($alarms as $alarmGroup) {
            $this->busUtils->setUserId($alarmGroup->getUserId());
            $dates = DateService::getDatesInRange(
                $start,
                DateService::getNextNthMonthEnd(2),
                $alarmGroup->getInterval(),
                $alarmGroup->getIntervalType(),
                $alarmGroup->getStart(),
                $alarmGroup->getStop()
            );
            Log::info('alarms:periodic-create', [
                'id' => $cronId,
                'dates' => $dates->get()
            ]);
            $this->commandBus->handle(
                CreateAlarmsForPeriodicAlarm::fromPeriodicAlarm(
                    $dates,
                    $alarmGroup,
                    null,
                    new NotificationsGroupsList(...$alarmGroup->getNotificationsGroups())
                )
            );
        }
        Log::info('alarms:periodic-create', [
            'id' => $cronId,
            'finish' => true
        ]);
        return 0;
    }
}
