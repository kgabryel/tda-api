<?php

namespace App\Shared\Domain\List;

use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\SyncResult;

interface AlarmsGroupsListInterface
{
    public function getIds(): array;

    public function sync(AlarmsGroupId ...$ids): SyncResult;

    public function detach(AlarmsGroupId $id): bool;
}
