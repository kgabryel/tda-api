<?php

namespace App\Video\Application;

use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\ViewModel\Video;

interface ReadRepository
{
    public function findById(VideoId $videoId, UserId $userId): Video;

    public function find(UserId $userId, VideoId ...$videosIds): array;

    public function findAll(UserId $userId): array;
}
