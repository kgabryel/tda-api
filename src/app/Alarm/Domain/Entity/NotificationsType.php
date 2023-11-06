<?php

namespace App\Alarm\Domain\Entity;

class NotificationsType
{
    private NotificationTypeId $typeId;
    private NotificationTypeValue $name;

    public function __construct(NotificationTypeId $typeId, NotificationTypeValue $name)
    {
        $this->typeId = $typeId;
        $this->name = $name;
    }

    public function getTypeId(): NotificationTypeId
    {
        return $this->typeId;
    }

    public function getName(): NotificationTypeValue
    {
        return $this->name;
    }
}
