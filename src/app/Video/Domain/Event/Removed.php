<?php

namespace App\Video\Domain\Event;

use App\Core\Cqrs\Event;
use App\Video\Domain\Entity\Video;

/**
 * Film zostala usuniety, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Removed implements Event
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
