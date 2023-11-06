<?php

namespace App\Catalog\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane katalogi zostaly zmodyfikowane, zostaly odpiete lub przypiete
 */
interface CatalogsModified extends AsyncEvent
{
    public function getIds(): array;

    public function getUserId(): UserId;
}
