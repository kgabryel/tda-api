<?php

namespace App\Video\Domain\Event;

use App\Core\Cqrs\Event;
use App\Shared\Domain\Entity\VideoId;

/**
 * Film zostal usuniety, nalezy usunac go z bazy danych
 */
class Deleted implements Event
{
    private VideoId $videoId;

    public function __construct(VideoId $videoId)
    {
        $this->videoId = $videoId;
    }

    public function getVideoId(): VideoId
    {
        return $this->videoId;
    }
}
