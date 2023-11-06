<?php

namespace App\Alarm\Infrastructure\Dto;

use App\Alarm\Domain\Entity\AlarmsInFuture as AlarmsInFutureInterface;
use App\Alarm\Infrastructure\Model\Alarm;
use App\Shared\Domain\Entity\AlarmId;
use Illuminate\Database\Eloquent\Builder;

class AlarmsInFuture implements AlarmsInFutureInterface
{
    private Builder $alarms;

    public function __construct(Builder $alarms)
    {
        $this->alarms = $alarms;
    }

    public function getIds(): array
    {
        return $this->alarms->pluck('id')->map(static fn(string $id) => new AlarmId($id))->toArray();
    }

    public function get(): array
    {
        return $this->alarms->get()->map(static fn(Alarm $alarm) => $alarm->toDomainModel())->toArray();
    }

    public function delete(): void
    {
        $this->alarms->delete();
    }
}
