<?php

namespace App\Alarm\Domain\Entity;

class NotificationTypesList
{
    private array $ids;

    public function __construct(NotificationTypeId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(NotificationTypeId $typeId) => $typeId->getValue(), $this->ids);
    }
}
