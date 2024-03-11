<?php

namespace App\Bookmark\Application\EventHandler;

use App\Bookmark\Application\BookmarkManagerInterface;

abstract class EventHandler
{
    protected BookmarkManagerInterface $bookmarkManager;

    public function __construct(BookmarkManagerInterface $bookmarkManager)
    {
        $this->bookmarkManager = $bookmarkManager;
    }
}
