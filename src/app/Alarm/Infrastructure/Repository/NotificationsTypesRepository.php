<?php

namespace App\Alarm\Infrastructure\Repository;

use App\Alarm\Application\NotificationsTypesRepository as RepositoryInterface;
use App\Alarm\Domain\Entity\NotificationTypeValue;
use App\Alarm\Infrastructure\Model\NotificationsType;

class NotificationsTypesRepository implements RepositoryInterface
{
    public function findAllAsViewModels(): array
    {
        return NotificationsType::all()->map(static fn(NotificationsType $type) => $type->toViewModel())->toArray();
    }

    public function findAllAsDomainModels(): array
    {
        return NotificationsType::all()->map(static fn(NotificationsType $type) => $type->toDomainModel())->toArray();
    }

    public function exists(NotificationTypeValue $name): bool
    {
        return NotificationsType::where('name', '=', $name->value)->exists();
    }
}
