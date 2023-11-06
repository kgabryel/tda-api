<?php

namespace App\Color\Infrastructure;

use App\Color\Application\ReadRepository;
use App\Color\Application\ViewModel\Color;
use App\Color\Domain\Entity\ColorId;
use App\Color\Infrastructure\Model\Color as ColorModel;
use App\Shared\Domain\Entity\UserId;

class ColorsReadRepository implements ReadRepository
{
    public function findAll(UserId $userId): array
    {
        return ColorModel::where('user_id', '=', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(ColorModel $color) => $color->toViewModel())
            ->toArray();
    }

    public function findById(ColorId $colorId, UserId $userId): Color
    {
        return ColorModel::where('id', '=', $colorId)
            ->where('user_id', '=', $userId)
            ->firstOrFail()
            ->toViewModel();
    }
}
