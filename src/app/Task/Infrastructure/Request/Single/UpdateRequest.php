<?php

namespace App\Task\Infrastructure\Request\Single;

use App\Shared\Domain\Config;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\CorrectMainTaskValue;
use App\Shared\Infrastructure\Rules\CorrectTaskAlarmValue;
use Carbon\Carbon;
use DateTimeImmutable;

class UpdateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|filled|max:%s', Config::TASK_NAME_LENGTH),
            'content' => 'nullable|string',
            'date' => 'nullable|date',
            'mainTask' => ['nullable', 'string', new CorrectMainTaskValue()],
            'alarm' => [
                'nullable',
                'string',
                sprintf('exists:alarms,id,user_id,%s', $this->userId),
                new CorrectTaskAlarmValue($this)
            ]
        ];
    }

    public function nameFilled(): bool
    {
        return $this->has('name');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function contentFilled(): bool
    {
        return $this->has('content');
    }

    public function getText(): ?string
    {
        return $this->get('content');
    }

    public function dateFilled(): bool
    {
        return $this->has('date');
    }

    public function getDate(): ?DateTimeImmutable
    {
        if ($this->date === null) {
            return null;
        }

        return (new Carbon($this->date))->toDateTimeImmutable();
    }

    public function mainTaskFilled(): bool
    {
        return $this->has('mainTask');
    }

    public function getMainTask(): ?TaskId
    {
        if ($this->mainTask === null) {
            return null;
        }

        return new TaskId($this->mainTask);
    }

    public function alarmFilled(): bool
    {
        return $this->has('alarm');
    }

    public function getAlarm(): ?AlarmId
    {
        if ($this->alarm === null) {
            return null;
        }

        return new AlarmId($this->alarm);
    }
}
