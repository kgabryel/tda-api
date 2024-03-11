<?php

namespace App\Task\Application\Command\RemoveFile;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\FileId;

/**
 * Odpina plik od zadania
 */
#[CommandHandler(RemoveFileHandler::class)]
class RemoveFile implements Command
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
