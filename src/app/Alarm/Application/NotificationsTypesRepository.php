<?php

namespace App\Alarm\Application;

use App\Alarm\Domain\Entity\NotificationTypeValue;

interface NotificationsTypesRepository
{
    public function findAllAsViewModels(): array;

    public function findAllAsDomainModels(): array;

    public function exists(NotificationTypeValue $name): bool;
}
