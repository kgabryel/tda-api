<?php

namespace App\File\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\TaskPinnable;
use Illuminate\Http\UploadedFile;

class UpdateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('nullable|string|filled|max:%s', Config::FILE_NAME_LENGTH),
            'file' => sprintf('nullable|file|max:%s', Config::MAX_FILE_SIZE),
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

    public function assignedToDashboardFilled(): bool
    {
        return $this->has('assignedToDashboard');
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function fileFilled(): bool
    {
        return $this->has('file');
    }

    public function getFile(): UploadedFile
    {
        return $this->file('file');
    }

    public function isAssignedToDashboard(): ?bool
    {
        return $this->assignedToDashboard;
    }

    public function getCatalogs(): ?array
    {
        return $this->catalogs;
    }

    public function getTasks(): ?array
    {
        return $this->tasks;
    }
}
