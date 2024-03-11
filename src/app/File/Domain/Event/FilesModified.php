<?php

namespace App\File\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane pliki zostaly zmodyfikowane, zostaly odpiete lub przypiete do zadania lub katalogu
 */
interface FilesModified extends AsyncEvent
{
    public function getIds(): array;

    public function getUserId(): UserId;
}
