<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\AlarmsGroupId;

class AlarmsGroupsIdsList
{
    private array $ids;

    public function __construct(AlarmsGroupId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(AlarmsGroupId $alarmId) => $alarmId->getValue(), $this->ids);
    }
}
