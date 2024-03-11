<?php

namespace App\Bookmark\Domain\Event;

use App\Bookmark\Domain\Entity\Bookmark;
use App\Core\Cqrs\Event;

/**
 * Zakladka zostala usunieta, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Removed implements Event
{
    private Bookmark $bookmark;

    public function __construct(Bookmark $bookmark)
    {
        $this->bookmark = $bookmark;
    }

    public function getBookmark(): Bookmark
    {
        return $this->bookmark;
    }
}
