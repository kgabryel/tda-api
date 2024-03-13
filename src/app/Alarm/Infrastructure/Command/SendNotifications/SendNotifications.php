<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications;

use App\Alarm\Application\Command\SingleAlarm\CheckNotification\CheckNotification;
use App\Alarm\Application\Query\FindByNotificationId\FindByNotificationId;
use App\Alarm\Domain\Entity\NotificationTypeValue;
use App\Alarm\Domain\Entity\SingleAlarm;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;
use App\Alarm\Infrastructure\Command\SendNotifications\Notification\EmailNotification;
use App\Alarm\Infrastructure\Command\SendNotifications\Notification\NotificationInterface;
use App\Alarm\Infrastructure\Command\SendNotifications\Notification\PushNotification;
use App\Alarm\Infrastructure\Command\SendNotifications\Notification\WebNotification;
use App\Alarm\Infrastructure\Model\NotificationBuff;
use App\Alarm\Infrastructure\Repository\AlarmsWriteRepository;
use App\Core\BusUtils;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Service\WebPushServiceInterface;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    protected $signature = 'notifications:send';
    private CommandBus $commandBus;
    private QueryBus $queryBus;
    private EventEmitter $eventEmitter;
    private AlarmsWriteRepository $repository;
    private WebPushServiceInterface $webPushService;
    private BusUtils $busUtils;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        AlarmsWriteRepository $repository,
        WebPushServiceInterface $webPushService,
        BusUtils $busUtils
    ) {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->eventEmitter = $eventEmitter;
        $this->repository = $repository;
        $this->webPushService = $webPushService;
        $this->busUtils = $busUtils;
    }

    public function handle(): int
    {
        $notifications = $this->repository->getNotificationsToSend();
        $query = false;
        if (!$notifications->isEmpty()) {
            $query = $notifications->toQuery();
            $query->update(['locked' => true]);
        }
        $notifications = $notifications->map(
            static fn(NotificationBuff $notificationBuff) => NotificationToSend::create($notificationBuff)
        );
        $toSend = [];
        /** @var NotificationToSend $notification */
        foreach ($notifications as $notification) {
            /** @var NotificationTypeValue $type */
            foreach ($notification->getTypes() as $type) {
                $toSend[] = match ($type) {
                    NotificationTypeValue::WEB => new WebNotification($notification),
                    NotificationTypeValue::EMAIL => new EmailNotification($notification),
                    NotificationTypeValue::PUSH => new PushNotification($notification, $this->webPushService)
                };
            }
        }
        $toSend = array_filter($toSend, static fn(NotificationInterface $item) => $item->isAvailable());
        array_walk($toSend, function(NotificationInterface $item) {
            $item->send();
            $this->busUtils->setUserId($item->getUserId());
            $this->commandBus->handle(new CheckNotification($item->getNotificationId()));
            /** @var SingleAlarm $alarm */
            $alarm = $this->queryBus->handle(new FindByNotificationId($item->getNotificationId()));
            $this->eventEmitter->emit(new AlarmsModified($alarm->getUserId(), $alarm->getAlarmId()));
        });
        if ($query !== false) {
            $query->delete();
        }

        return 0;
    }
}
