<?php

namespace App\Bookmark\Infrastructure;

use App\Bookmark\Domain\Entity\Bookmark;
use App\Bookmark\Domain\WriteRepository;
use App\Bookmark\Infrastructure\Model\Bookmark as BookmarkModel;
use App\Core\Cache;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;

class BookmarksWriteRepository implements WriteRepository
{
    public function findById(BookmarkId $bookmarkId, UserId $userId): Bookmark
    {
        $bId = $bookmarkId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(BookmarkManager::getCacheKey($bookmarkId), static function () use ($bId, $uId) {
            return BookmarkModel::where('id', '=', $bId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }
}
