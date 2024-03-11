<?php

namespace App\Alarm\Application\ViewModel;

use JsonSerializable;

class NotificationGroup implements JsonSerializable
{
    private int $id;
    private string $hour;
    private string $behaviour;
    private ?int $interval;
    private NotificationsTypesList $notificationsTypesList;

    public function __construct(
        int $id,
        string $hour,
        string $behaviour,
        ?int $interval,
        NotificationsTypesList $notificationsTypesList
    ) {
        $this->id = $id;
        $this->hour = $hour;
        $this->behaviour = $behaviour;
        $this->interval = $interval;
        $this->notificationsTypesList = $notificationsTypesList;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'hour' => $this->hour,
            'behaviour' => $this->behaviour,
            'interval' => $this->interval,
            'types' => $this->notificationsTypesList->getIds()
        ];
    }
}
