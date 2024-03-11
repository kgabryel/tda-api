<?php

namespace App\Alarm\Infrastructure\Command;

use App\Alarm\Application\Command\SingleAlarm\AddNotificationToBuff\AddNotificationToBuff;
use App\Alarm\Application\Query\FindNotificationBetweenDates\FindNotificationsBetweenDates;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\DateService;
use Illuminate\Console\Command;

class InsertToBuff extends Command
{
    protected $signature = 'notifications:insert';
    private QueryBus $queryBus;
    private CommandBus $commandBus;

    public function __construct(QueryBus $queryBus, CommandBus $commandBus)
    {
        parent::__construct();
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    public function handle(): int
    {
        $notifications = $this->queryBus->handle(
            new FindNotificationsBetweenDates(DateService::getNextMonthStart(), DateService::getNextMonthEnd())
        );
        foreach ($notifications as $notification) {
            $this->commandBus->handle(new AddNotificationToBuff($notification));
        }

        return 0;
    }
}
