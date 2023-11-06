<?php

namespace App\Bookmark\Infrastructure;

use App\Bookmark\Application\ReadRepository;
use App\Bookmark\Application\ViewModel\Bookmark;
use App\Bookmark\Infrastructure\Model\Bookmark as BookmarkModel;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;

class BookmarksReadRepository implements ReadRepository
{
    public function findById(BookmarkId $bookmarkId, UserId $userId): Bookmark
    {
        return BookmarkModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('id', '=', $bookmarkId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function find(UserId $userId, BookmarkId ...$bookmarksIds): array
    {
        return BookmarkModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->whereIn('id', $bookmarksIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(BookmarkModel $bookmark) => $bookmark->toViewModel())
            ->toArray();
    }

    public function findAll(UserId $userId): array
    {
        return BookmarkModel::with(['catalogs:id', 'tasks:id', 'tasksGroups:id'])
            ->where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(BookmarkModel $bookmark) => $bookmark->toViewModel())
            ->toArray();
    }
}
