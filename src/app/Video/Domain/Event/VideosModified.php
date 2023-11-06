<?php

namespace App\Video\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane filmu zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania lub katalogu
 */
interface VideosModified extends AsyncEvent
{
    public function getIds(): array;

    public function getUserId(): UserId;
}
