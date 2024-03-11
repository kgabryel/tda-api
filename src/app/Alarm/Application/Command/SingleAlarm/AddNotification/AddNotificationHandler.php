<?php

namespace App\Alarm\Application\Command\SingleAlarm\AddNotification;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\NotificationManagerInterface;
use App\Alarm\Domain\Event\SingleAlarm\Updated;
use App\Alarm\Domain\Exception\AssignedAlarmModified;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;

class AddNotificationHandler extends ModifyAlarmHandler
{
    private NotificationManagerInterface $notificationManager;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        NotificationManagerInterface $notificationManager,
        CommandBus $commandBus
    ) {
        parent::__construct($queryBus, $eventEmitter, $commandBus);
        $this->notificationManager = $notificationManager;
    }

    public function handle(AddNotification $command): void
    {
        $alarm = $this->getSingleAlarm($command->getNotification()->getAlarmId());
        /**
         * Jezeli alarm pojedynczy posiada przypisany alarm okresowy to nie moze zostac do niego nowe powiadomienie.
         * Wyjatkiem jest dodawanie powiadomien podczas tworzenia alarmow pojedynczych dla alarmu okresowego.
         * W takim przypadku komenda posiada ustawiona wartosc 'fromGroup' na true
         */
        if ($alarm->hasGroup() && !$command->isFromGroup()) {
            throw new AssignedAlarmModified();
        }
        $notification = $this->notificationManager->create(
            $alarm->getAlarmId(),
            $alarm->getUserId(),
            $command->getNotification()->getTime()
        );
        if ($alarm->addNotification($notification, $command->isFromGroup())) {
            $this->eventEmitter->emit(new Updated($alarm));
        }
    }
}
