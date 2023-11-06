<?php

namespace App\Color\Infrastructure;

use App\Color\Domain\Entity\Color;
use App\Color\Domain\Entity\ColorId;
use App\Color\Domain\WriteRepository;
use App\Color\Infrastructure\Model\Color as ColorModel;
use App\Core\Cache;
use App\Shared\Domain\Entity\UserId;

class ColorsWriteRepository implements WriteRepository
{
    public function findById(ColorId $colorId, UserId $userId): Color
    {
        $cId = $colorId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(ColorManager::getCacheKey($colorId), static function () use ($cId, $uId) {
            return ColorModel::where('id', '=', $cId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }
}
