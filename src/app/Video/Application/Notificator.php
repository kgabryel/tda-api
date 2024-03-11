<?php

namespace App\Video\Application;

use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Entity\VideoId;

interface Notificator
{
    public function videosChanges(UserId|int $user, VideoId ...$ids): void;
}
