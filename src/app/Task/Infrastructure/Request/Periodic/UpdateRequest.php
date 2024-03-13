<?php

namespace App\Task\Infrastructure\Request\Periodic;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\User\Infrastructure\StringUtils;

class UpdateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|filled|max:%s', Config::TASK_NAME_LENGTH),
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
        return StringUtils::trimContent($this->get('content'));
    }
}
