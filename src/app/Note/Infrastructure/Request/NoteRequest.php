<?php

namespace App\Note\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\TaskPinnable;
use LVR\Colour\Hex;

class NoteRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|max:%s', Config::NOTE_NAME_LENGTH),
            'backgroundColor' => ['required', 'string', new Hex()],
            'textColor' => ['required', 'string', new Hex()],
            'assignedToDashboard' => 'required|boolean',
            'tasks' => 'present|array',
            'tasks.*' => ['string', new TaskPinnable()],
            'catalogs' => 'present|array',
            'catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getText(): string
    {
        return (string)$this->get('content');
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
