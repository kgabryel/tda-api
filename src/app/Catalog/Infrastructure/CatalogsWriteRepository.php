<?php

namespace App\Catalog\Infrastructure;

use App\Catalog\Domain\Entity\Catalog;
use App\Catalog\Domain\WriteRepository;
use App\Catalog\Infrastructure\Model\Catalog as CatalogModel;
use App\Core\Cache;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\UserId;

class CatalogsWriteRepository implements WriteRepository
{
    public function findById(CatalogId $catalogId, UserId $userId): Catalog
    {
        $cId = $catalogId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(CatalogManager::getCacheKey($catalogId), static function() use ($cId, $uId) {
            return CatalogModel::where('id', '=', $cId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }
}
