<?php

namespace App\Bookmark\Infrastructure;

use App\Bookmark\Application\BookmarkManagerInterface;
use App\Bookmark\Domain\Entity\Bookmark as DomainModel;
use App\Bookmark\Infrastructure\Model\Bookmark;
use App\Core\Cache;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;

class BookmarkManager implements BookmarkManagerInterface
{
    public function delete(BookmarkId $bookmarkId): void
    {
        $this->getModel($bookmarkId)->delete();
        Cache::forget(self::getCacheKey($bookmarkId));
    }

    private function getModel(BookmarkId $bookmarkId): Bookmark
    {
        $bookmark = new Bookmark();
        $bookmark->id = $bookmarkId->getValue();
        $bookmark->exists = true;

        return $bookmark;
    }

    public static function getCacheKey(BookmarkId $bookmarkId): string
    {
        return sprintf('bookmarks-%s', $bookmarkId);
    }

    public function create(
        string $name,
        string $href,
        ?string $icon,
        bool $assignedToDashboard,
        Hex $textColor,
        Hex $backgroundColor,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): DomainModel {
        $bookmark = new Bookmark();
        $bookmark->setName($name)
            ->setHref($href)
            ->setIcon($icon)
            ->setAssignedToDashboard($assignedToDashboard)
            ->setBackgroundColor($backgroundColor->getColor())
            ->setTextColor($textColor->getColor())
            ->setUserid($userId)
            ->save();
        $bookmark->catalogs()->attach($catalogsList->getIds());
        $bookmark->tasks()->attach($tasksList->getIds());
        $bookmark->tasksGroups()->attach($tasksGroupsList->getIds());
        $domainModel = $bookmark->toDomainModel();
        $key = self::getCacheKey($domainModel->getBookmarkId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }

    public function update(DomainModel $bookmark): void
    {
        $this->getModel($bookmark->getBookmarkId())
            ->setName($bookmark->getName())
            ->setTextColor($bookmark->getTextColor()->getColor())
            ->setBackgroundColor($bookmark->getBackgroundColor()->getColor())
            ->setAssignedToDashboard($bookmark->isAssignedToDashboard())
            ->setIcon($bookmark->getIcon())
            ->setHref($bookmark->getHref())
            ->update();
    }
}
