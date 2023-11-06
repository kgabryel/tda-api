<?php

namespace App\Video\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\TaskPinnable;

class CreateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'href' => 'required|filled|string|url',
            'tasks' => 'present|array',
            'assignedToDashboard' => 'required|boolean',
            'tasks.*' => ['string', new TaskPinnable()],
            'catalogs' => 'present|array',
            'catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getHref(): string
    {
        return $this->href;
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
