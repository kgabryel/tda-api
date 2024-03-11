<?php

namespace App\Video\Domain\Event;

use App\Core\Cqrs\Event;
use App\Video\Domain\Entity\Video;

/**
 * Film zostal zmodyfikowany, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
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
