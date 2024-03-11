<?php

namespace App\Task\Application\Command\RemoveBookmark;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Odpina zaklade od zadania
 */
#[CommandHandler(RemoveBookmarkHandler::class)]
class RemoveBookmark implements Command
{
    private string $taskId;
    private BookmarkId $bookmarkId;

    public function __construct(string $taskId, BookmarkId $bookmarkId)
    {
        $this->taskId = $taskId;
        $this->bookmarkId = $bookmarkId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getBookmarkId(): BookmarkId
    {
        return $this->bookmarkId;
    }
}
