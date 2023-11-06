<?php

namespace App\File\Application\Command\ReplaceFile;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\File\Application\Command\ModifyFileHandler;
use App\File\Application\DeleteFileServiceInterface;
use App\File\Domain\Entity\File;
use App\File\Domain\Event\FileReplaced;
use App\Shared\Application\UuidInterface;

class ReplaceFileHandler extends ModifyFileHandler
{
    private UuidInterface $uuid;
    private DeleteFileServiceInterface $deleteFileService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        UuidInterface $uuid,
        DeleteFileServiceInterface $deleteFileService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->uuid = $uuid;
        $this->deleteFileService = $deleteFileService;
    }

    public function handle(ReplaceFile $command): void
    {
        $path = $this->uuid->getValue();
        $file = $this->getFile($command->getFileId());
        $uploadedFile = $command->getFile();
        $uploadedFile->storeAs(File::STORAGE_DIRECTORY, $path);
        $oldPath = $file->getFullPath();
        $file->replaceFile($uploadedFile);
        $this->eventEmitter->emit(new FileReplaced($file->getFileId(), $uploadedFile, $path));
        $this->deleteFileService->delete($oldPath);
    }
}
