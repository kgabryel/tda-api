<?php

namespace App\Video\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\TaskPinnable;

class UpdateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|max:%s', Config::VIDEO_NAME_LENGTH),
            'watched' => 'nullable|boolean',
            'assignedToDashboard' => 'nullable|boolean',
            'tasks' => 'nullable|array',
            'tasks.*' => ['string', new TaskPinnable()],
            'catalogs' => 'nullable|array',
            'catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function nameFilled(): bool
    {
        return $this->has('name');
    }

    public function tasksFilled(): bool
    {
        return $this->has('tasks');
    }

    public function catalogsFilled(): bool
    {
        return $this->has('catalogs');
    }

    public function isWatchedFilled(): bool
    {
        return $this->has('watched');
    }

    public function assignedToDashboardFilled(): bool
    {
        return $this->has('assignedToDashboard');
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function isWatched(): ?bool
    {
        return $this->watched;
    }

    public function isAssignedToDashboard(): ?bool
    {
        return $this->assignedToDashboard;
    }

    public function getCatalogs(): array
    {
        return $this->catalogs ?? [];
    }

    public function getTasks(): array
    {
        return $this->tasks ?? [];
    }
}
