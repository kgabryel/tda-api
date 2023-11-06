<?php

namespace App\Task\Application\Command\AddFile;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\FileId;

/**
 * Przypina plik do zadania
 */
#[CommandHandler(AddFileHandler::class)]
class AddFile implements Command
{
    private string $taskId;
    private FileId $fileId;

    public function __construct(string $taskId, FileId $fileId)
    {
        $this->taskId = $taskId;
        $this->fileId = $fileId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getFileId(): FileId
    {
        return $this->fileId;
    }
}
