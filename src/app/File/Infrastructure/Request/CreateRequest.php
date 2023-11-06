<?php

namespace App\File\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\TaskPinnable;
use Illuminate\Http\UploadedFile;

class CreateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'name' => sprintf('required|string|filled|max:%s', Config::FILE_NAME_LENGTH),
            'assignedToDashboard' => 'required|boolean',
            'file' => sprintf('required|file|max:%s', Config::MAX_FILE_SIZE),
            'tasks' => 'nullable|array',
            'tasks.*' => ['string', new TaskPinnable()],
            'catalogs' => 'nullable|array',
            'catalogs.*' => sprintf('integer|exists:catalogs,id,user_id,%s', $this->userId)
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFile(): UploadedFile
    {
        return $this->file('file');
    }

    public function isAssignedToDashboard(): bool
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
