<?php

namespace App\File\Application;

use App\File\Domain\Entity\File;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

interface FileManagerInterface
{
    public function update(File $file): void;

    public function replaceFile(FileId $fileId, string $path, UploadedFileInterface $uploadedFile): void;

    public function delete(FileId $fileId): void;

    public function create(
        string $name,
        bool $assignedToDashboard,
        string $path,
        UploadedFileInterface $uploadedFile,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): File;
}
