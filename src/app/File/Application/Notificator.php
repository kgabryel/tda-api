<?php

namespace App\File\Application;

use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

interface Notificator
{
    public function filesChanges(UserId|int $user, FileId ...$ids): void;
}
