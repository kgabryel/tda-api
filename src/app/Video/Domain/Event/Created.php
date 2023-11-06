<?php

namespace App\Video\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Video\Domain\Entity\Video;

/**
 * Film zostal dodany, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Created implements AsyncEvent
{
    private Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function getVideo(): Video
    {
        return $this->video;
    }
}
