<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\SyncResult;

interface AlarmsListInterface
{
    public function getIds(): array;

    public function sync(AlarmId ...$ids): SyncResult;

    public function detach(AlarmId $id): bool;
}
