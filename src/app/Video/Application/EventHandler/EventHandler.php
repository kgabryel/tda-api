<?php

namespace App\Video\Application\EventHandler;

use App\Video\Application\VideoManagerInterface;

abstract class EventHandler
{
    protected VideoManagerInterface $videoManager;

    public function __construct(VideoManagerInterface $videoManager)
    {
        $this->videoManager = $videoManager;
    }
}
