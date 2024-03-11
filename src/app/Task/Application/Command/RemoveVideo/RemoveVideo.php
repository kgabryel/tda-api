<?php

namespace App\Task\Application\Command\RemoveVideo;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\VideoId;

/**
 * Odpina film od zadania
 */
#[CommandHandler(RemoveVideoHandler::class)]
class RemoveVideo implements Command
{
    private string $taskId;
    private VideoId $videoId;

    public function __construct(string $taskId, VideoId $videoId)
    {
        $this->taskId = $taskId;
        $this->videoId = $videoId;
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getVideoId(): VideoId
    {
        return $this->videoId;
    }
}
