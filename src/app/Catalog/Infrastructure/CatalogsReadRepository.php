<?php

namespace App\Catalog\Infrastructure;

use App\Catalog\Application\ReadRepository;
use App\Catalog\Application\ViewModel\Catalog;
use App\Catalog\Infrastructure\Model\Catalog as CatalogModel;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

class CatalogsReadRepository implements ReadRepository
{
    public function findById(CatalogId $catalogId, UserId $userId): Catalog
    {
        return CatalogModel::with(
            [
                'alarms:id',
                'alarmsGroups:id',
                'tasks:id',
                'tasksGroups:id',
                'notes:id',
                'bookmarks:id',
                'files:id',
                'videos:id'
            ]
        )
            ->where('id', '=', $catalogId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }

    public function find(UserId $userId, CatalogId ...$catalogsIds): array
    {
        return CatalogModel::with(
            [
                'alarms:id',
                'alarmsGroups:id',
                'tasks:id',
                'tasksGroups:id',
                'notes:id',
                'bookmarks:id',
                'files:id',
                'videos:id'
            ]
        )
            ->where('user_id', '=', $userId)
            ->whereIn('id', $catalogsIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(CatalogModel $catalog) => $catalog->toViewModel())
            ->toArray();
    }

    public function findAll(UserId $userId): array
    {
        return CatalogModel::with(
            [
                'alarms:id',
                'alarmsGroups:id',
                'tasks:id',
                'tasksGroups:id',
                'notes:id',
                'bookmarks:id',
                'files:id',
                'videos:id'
            ]
        )
            ->where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(CatalogModel $catalog) => $catalog->toViewModel())
            ->toArray();
    }
}
