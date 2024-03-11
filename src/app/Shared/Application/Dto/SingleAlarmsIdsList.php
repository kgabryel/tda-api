<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\AlarmId;

class SingleAlarmsIdsList
{
    private array $ids;

    public function __construct(AlarmId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function add(AlarmId $alarmId): void
    {
        $this->ids[] = $alarmId;
    }

    public function getIds(): array
    {
        return array_map(static fn(AlarmId $alarmId) => $alarmId->getValue(), $this->ids);
    }

    public function isEmpty(): bool
    {
        return $this->ids === [];
    }
}
