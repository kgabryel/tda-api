<?php

namespace App\Task\Domain\Event;

use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Domain\Event\VideosModified;

/**
 * Podane filmy zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania
 */
class VideosAssigmentChanged implements VideosModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, VideoId ...$ids)
    {
        $this->ids = $ids;
        $this->userId = $userId;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
