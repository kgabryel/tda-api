<?php

namespace App\Catalog\Infrastructure;

use App\Catalog\Application\CatalogManagerInterface;
use App\Catalog\Domain\Entity\Catalog as DomainModel;
use App\Catalog\Infrastructure\Model\Catalog;
use App\Core\Cache;
use App\Shared\Application\Dto\AlarmsGroupsIdsList;
use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\SingleAlarmsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Application\Dto\VideosIdsList;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

class CatalogManager implements CatalogManagerInterface
{
    public function update(DomainModel $catalog): void
    {
        $this->getModel($catalog->getCatalogId())
            ->setName($catalog->getName())
            ->setAssignedToDashboard($catalog->isAssignedToDashboard())
            ->update();
    }

    private function getModel(CatalogId $catalogId): Catalog
    {
        $catalog = new Catalog();
        $catalog->id = $catalogId->getValue();
        $catalog->exists = true;

        return $catalog;
    }

    public function delete(CatalogId $catalogId): void
    {
        $this->getModel($catalogId)->delete();
        Cache::forget(self::getCacheKey($catalogId));
    }

    public static function getCacheKey(CatalogId $catalogId): string
    {
        return sprintf('catalogs-%s', $catalogId);
    }

    public function create(
        string $name,
        bool $assignedToDashboard,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        SingleAlarmsIdsList $alarmsList,
        AlarmsGroupsIdsList $alarmsGroupsList,
        BookmarksIdsList $bookmarksList,
        NotesIdsList $notesList,
        FilesIdsList $filesList,
        VideosIdsList $videosList,
        UserId $userId
    ): DomainModel {
        $catalog = new Catalog();
        $catalog->setName($name)
            ->setAssignedToDashboard($assignedToDashboard)
            ->setUserId($userId)
            ->save();
        $catalog->tasks()->attach($tasksList->getIds());
        $catalog->tasksGroups()->attach($tasksGroupsList->getIds());
        $catalog->bookmarks()->attach($bookmarksList->getIds());
        $catalog->files()->attach($filesList->getIds());
        $catalog->notes()->attach($notesList->getIds());
        $catalog->videos()->attach($videosList->getIds());
        $catalog->alarms()->attach($alarmsList->getIds());
        $catalog->alarmsGroups()->attach($alarmsGroupsList->getIds());
        $domainModel = $catalog->toDomainModel();
        $key = self::getCacheKey($domainModel->getCatalogId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }
}
