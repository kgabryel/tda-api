<?php

namespace App\File\Application\Command\Delete;

use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\File\Application\Command\ModifyFileHandler;
use App\File\Application\DeleteFileServiceInterface;
use App\File\Domain\Event\Deleted;
use App\File\Domain\Event\Removed;

class DeleteHandler extends ModifyFileHandler
{
    private DeleteFileServiceInterface $deleteFileService;

    public function __construct(
        QueryBus $queryBus,
        EventEmitter $eventEmitter,
        DeleteFileServiceInterface $deleteFileService
    ) {
        parent::__construct($queryBus, $eventEmitter);
        $this->deleteFileService = $deleteFileService;
    }

    public function handle(Delete $command): void
    {
        $file = $this->getFile($command->getFileId());
        $fullPath = $file->getFullPath();
        if (!$file->delete()) {
            return;
        }
        $this->eventEmitter->emit(new Removed($file));
        $this->eventEmitter->emit(new Deleted($file->getFileId()));
        $this->deleteFileService->delete($fullPath);
    }
}
