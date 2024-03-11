<?php

namespace App\Video\Application\Command;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\VideoId;

abstract class ModifyVideoCommand implements Command
{
    protected VideoId $videoId;

    public function __construct(VideoId $videoId)
    {
        $this->videoId = $videoId;
    }

    public function getVideoId(): VideoId
    {
        return $this->videoId;
    }
}
