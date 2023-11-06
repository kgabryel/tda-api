<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\VideoId;
use App\Shared\Domain\SyncResult;

interface VideosListInterface
{
    public function getIds(): array;

    public function sync(VideoId ...$ids): SyncResult;

    public function detach(VideoId $id): bool;

    public function attach(VideoId $id): bool;
}
