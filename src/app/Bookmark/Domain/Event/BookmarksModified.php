<?php

namespace App\Bookmark\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane zakladki zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania lub katalogu
 */
interface BookmarksModified extends AsyncEvent
{
    public function getIds(): array;

    public function getUserId(): UserId;
}
