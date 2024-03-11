<?php

namespace App\Alarm\Infrastructure\Request\Single;

use App\Core\Cqrs\QueryBus;
use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\CorrectAlarmTaskValue;

class UpdateRequest extends BasicRequest
{
    private QueryBus $queryBus;

    public function __construct(
        QueryBus $queryBus,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->queryBus = $queryBus;
    }

    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|filled|max:%s', Config::ALARM_NAME_LENGTH),
            'content' => 'nullable|string',
            'task' => ['nullable', 'string', new CorrectAlarmTaskValue($this, $this->queryBus)]
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

    public function taskFilled(): bool
    {
        return $this->has('task');
    }

    public function getTask(): ?string
    {
        return $this->task;
    }
}
