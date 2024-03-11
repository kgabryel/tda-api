<?php

namespace App\Color\Infrastructure;

use App\Color\Application\ColorManagerInterface;
use App\Color\Domain\Entity\Color as DomainModel;
use App\Color\Domain\Entity\ColorId;
use App\Color\Infrastructure\Model\Color;
use App\Core\Cache;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;

class ColorManager implements ColorManagerInterface
{
    public function delete(ColorId $colorId): void
    {
        $this->getModel($colorId)->delete();
        Cache::forget(self::getCacheKey($colorId));
    }

    private function getModel(ColorId $colorId): Color
    {
        $video = new Color();
        $video->id = $colorId->getValue();
        $video->exists = true;

        return $video;
    }

    public static function getCacheKey(ColorId $colorId): string
    {
        return sprintf('colors-%s', $colorId);
    }

    public function create(string $name, Hex $colorValue, UserId $userId): DomainModel
    {
        $color = new Color();
        $color->setName($name)
            ->setColor($colorValue->getColor())
            ->setUserId($userId)
            ->update();
        $domainModel = $color->toDomainModel();
        $key = self::getCacheKey($domainModel->getColorId());
        Cache::add($key, $domainModel);

        return Cache::get($key);
    }
}
