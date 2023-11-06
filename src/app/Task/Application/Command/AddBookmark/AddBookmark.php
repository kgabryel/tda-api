<?php

namespace App\Task\Application\Command\AddBookmark;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\BookmarkId;

/**
 * Przypina zakladke do zadania
 */
#[CommandHandler(AddBookmarkHandler::class)]
class AddBookmark implements Command
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
