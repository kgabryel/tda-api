<?php

namespace App\Alarm\Application\ViewModel;

use DateTimeImmutable;
use JsonSerializable;

class Notification implements JsonSerializable
{
    private int $id;
    private DateTimeImmutable $time;
    private bool $isChecked;
    private NotificationsTypesList $notificationsTypesList;

    public function __construct(
        int $id,
        DateTimeImmutable $time,
        bool $isChecked,
        NotificationsTypesList $notificationsTypesList
    ) {
        $this->id = $id;
        $this->time = $time;
        $this->isChecked = $isChecked;
        $this->notificationsTypesList = $notificationsTypesList;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'time' => $this->time->format('Y-m-d H:i:s'),
            'checked' => $this->isChecked,
            'types' => $this->notificationsTypesList->getIds()
        ];
    }
}
