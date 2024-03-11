<?php

namespace App\Alarm\Domain\Entity;

interface NotificationsList
{
    public function check(): void;

    public function get(): array;

    public function delete(NotificationId $id): void;

    public function find(NotificationId $id): Notification;
}
