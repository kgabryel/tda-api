<?php

namespace App\File\Infrastructure;

use App\File\Application\ReadRepository;
use App\File\Application\ViewModel\File;
use App\File\Infrastructure\Model\File as FileModel;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

class FilesReadRepository implements ReadRepository
{
    public function findById(FileId $fileId, UserId $userId): File
    {
        return FileModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('id', '=', $fileId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function find(UserId $userId, FileId ...$filesIds): array
    {
        return FileModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->whereIn('id', $filesIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(FileModel $file) => $file->toViewModel())
            ->toArray();
    }

    public function findAll(UserId $userId): array
    {
        return FileModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(FileModel $file) => $file->toViewModel())
            ->toArray();
    }
}
