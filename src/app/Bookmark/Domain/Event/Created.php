<?php

namespace App\Bookmark\Domain\Event;

use App\Bookmark\Domain\Entity\Bookmark;
use App\Core\Cqrs\AsyncEvent;

/**
 * Zaklada zostala dodana, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Created implements AsyncEvent
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
