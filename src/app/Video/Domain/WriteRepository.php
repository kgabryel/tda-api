<?php

namespace App\Video\Domain;

use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Domain\Entity\Video;

interface WriteRepository
{
    public function findById(VideoId $videoId, UserId $userId): Video;
}
