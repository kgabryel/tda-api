<?php

namespace App\Bookmark\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\TaskPinnable;
use LVR\Colour\Hex;

class UpdateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('required|filled|string|max:%s', Config::BOOKMARK_NAME_LENGTH),
            'href' => 'required|filled|string|url',
            'assignedToDashboard' => 'required|boolean',
            'backgroundColor' => ['required', 'string', new Hex()],
            'textColor' => ['required', 'string', new Hex()],
            'tasks' => 'present|array',
            'tasks.*' => ['string', new TaskPinnable()],
            'catalogs' => 'present|array',
            'catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId),
            'icon' => 'nullable|string|url'
        ];
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
