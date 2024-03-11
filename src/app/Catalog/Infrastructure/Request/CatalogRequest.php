<?php

namespace App\Catalog\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\AlarmPinnable;
use App\Shared\Infrastructure\Rules\TaskPinnable;

class CatalogRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|max:%s', Config::CATALOG_NAME_LENGTH),
            'assignedToDashboard' => 'required|boolean',
            'tasks' => 'present|array',
            'tasks.*' => ['string', new TaskPinnable()],
            'alarms' => 'present|array',
            'alarms.*' => ['string', new AlarmPinnable()],
            'notes' => 'present|array',
            'notes.*' => sprintf('integer|exists:notes,id,user_id,%s', $this->userId),
            'bookmarks' => 'present|array',
            'bookmarks.*' => sprintf('integer|exists:bookmarks,id,user_id,%s', $this->userId),
            'files' => 'present|array',
            'files.*' => sprintf('integer|exists:files,id,user_id,%s', $this->userId),
            'videos' => 'present|array',
            'videos.*' => sprintf('integer|exists:videos,id,user_id,%s', $this->userId)
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isAssignedToDashboard(): bool
    {
        return $this->assignedToDashboard;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function getAlarms(): array
    {
        return $this->alarms;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }

    public function getBookmarks(): array
    {
        return $this->bookmarks;
    }

    public function getFiles(): array
    {
        return $this->get('files');
    }

    public function getVideos(): array
    {
        return $this->videos;
    }
}
