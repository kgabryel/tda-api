<?php

namespace App\File\Infrastructure;

use App\Core\Cache;
use App\File\Application\FileManagerInterface;
use App\File\Application\UploadedFileInterface;
use App\File\Domain\Entity\File as DomainModel;
use App\File\Infrastructure\Model\File;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

class FileManager implements FileManagerInterface
{
    public function replaceFile(FileId $fileId, string $path, UploadedFileInterface $uploadedFile): void
    {
        $this->getModel($fileId)->setSize($uploadedFile->getSize())
            ->setParsedSize($uploadedFile->getParsedSize())
            ->setPath($path)
            ->setMimeType($uploadedFile->getMimeType())
            ->setExtension($uploadedFile->getExtension())
            ->setOriginalName($uploadedFile->getOriginalName())
            ->update();
    }

    public function update(DomainModel $file): void
    {
        $this->getModel($file->getFileId())
            ->setName($file->getName())
            ->setAssignedToDashboard($file->isAssignedToDashboard())
            ->update();
    }

    private function getModel(FileId $fileId): File
    {
        $file = new File();
        $file->id = $fileId->getValue();
        $file->exists = true;

        return $file;
    }

    public function create(
        string $name,
        bool $assignedToDashboard,
        string $path,
        UploadedFileInterface $uploadedFile,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): DomainModel {
        $file = new File();
        $file->setName($name)
            ->setAssignedToDashboard($assignedToDashboard)
            ->setUserId($userId)
            ->setSize($uploadedFile->getSize())
            ->setParsedSize($uploadedFile->getParsedSize())
            ->setPath($path)
            ->setMimeType($uploadedFile->getMimeType())
            ->setExtension($uploadedFile->getExtension())
            ->setOriginalName($uploadedFile->getOriginalName())
            ->save();
        $file->catalogs()->attach($catalogsList->getIds());
        $file->tasks()->attach($tasksList->getIds());
        $file->tasksGroups()->attach($tasksGroupsList->getIds());
        $domainModel = $file->toDomainModel();
        $key = self::getCacheKey($domainModel->getFileId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

    public static function getCacheKey(FileId $fileId): string
    {
        return sprintf('files-%s', $fileId);
    }

    public function delete(FileId $fileId): void
    {
        $this->getModel($fileId)->delete();
        Cache::forget(self::getCacheKey($fileId));
    }
}
