<?php

namespace App\Task\Application\Command\AddVideo;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\VideoId;

/**
 * Przypina film do zadania
 */
#[CommandHandler(AddVideoHandler::class)]
class AddVideo implements Command
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
