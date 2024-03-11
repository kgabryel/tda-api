<?php

namespace App\Bookmark\Domain\Event;

use App\Bookmark\Domain\Entity\Bookmark;
use App\Core\Cqrs\Event;

/**
 * Zakladka zostala zmodyfikowana, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
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
