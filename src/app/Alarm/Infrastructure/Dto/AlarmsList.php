<?php

namespace App\Alarm\Infrastructure\Dto;

use App\Alarm\Domain\Entity\AlarmsInFuture as AlarmsInFutureInterface;
use App\Alarm\Domain\Entity\AlarmsList as AlarmsListInterface;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;
use DateTime;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlarmsList implements AlarmsListInterface
{
    private HasMany $alarms;

    public function __construct(HasMany $alarms)
    {
        $this->alarms = $alarms;
    }

    public function updateName(string $name): void
    {
        $this->alarms->update(['name' => $name]);
    }

    public function updateContent(?string $content): void
    {
        $this->alarms->update(['content' => $content]);
    }

    public function getIds(): array
    {
        return $this->alarms->pluck('id')->map(static fn(string $id) => new AlarmId($id))->toArray();
    }

    public function delete(): void
    {
        $this->alarms->delete();
    }

    public function disconnect(): void
    {
        $this->alarms->update(['group_id' => null]);
    }

    public function getAlarmsInFuture(): AlarmsInFutureInterface
    {
        return new AlarmsInFuture($this->alarms->clone()->where('date', '>=', new DateTime()));
    }

    public function getConnectedTasksIds(): array
    {
        return $this->alarms->clone()->whereNotNull('task_id')
            ->pluck('task_id')
            ->map(static fn(string $id) => new TaskId($id))
            ->toArray();
    }
}
