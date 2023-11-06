<?php

namespace App\Alarm\Infrastructure\Command\SendNotifications;

use App\Alarm\Infrastructure\Model\Notification as NotificationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable;
    use SerializesModels;

    private string $lang;
    private array $notifications;

    private NotificationToSend $notificationToSend;

    public function __construct(NotificationToSend $notification)
    {
        $this->notificationToSend = $notification;

        $this->lang = $notification->getLang();
        $this->notifications = array_map(
            static fn(NotificationModel $notification) => [
                'time' => $notification->getTime()->format('Y-m-d H:i:s'),
                'types' => implode(', ', $notification->getTypesNames())
            ],
            $this->notificationToSend->getNotifications()
        );
    }

    public function build(): Notification
    {
        return $this->view('notification', [
            'name' => $this->notificationToSend->getName(),
            'content' => $this->notificationToSend->getContent(),
            'date' => $this->notificationToSend->getDate(),
            'notifications' => $this->notifications,
            'alarmDeactivationCode' => $this->notificationToSend->getDeactivationCode(),
            'alarmId' => $this->notificationToSend->getAlarmId(),
            'taskId' => $this->notificationToSend->getTaskId(),
            'groupId' => $this->notificationToSend->getGroupId(),
            'lang' => $this->lang
        ]);
    }
}
