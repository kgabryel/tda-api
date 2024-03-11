<?php

namespace App\Alarm\Infrastructure\Request\Periodic;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;

class UpdateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|filled|max:%s', Config::ALARM_NAME_LENGTH),
            'content' => 'nullable|string'
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
}
