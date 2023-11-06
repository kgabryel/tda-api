<?php

namespace App\Color\Application;

use App\Color\Application\ViewModel\Color;
use App\Color\Domain\Entity\ColorId;
use App\Shared\Domain\Entity\UserId;

interface ReadRepository
{
    public function findById(ColorId $colorId, UserId $userId): Color;

    public function findAll(UserId $userId): array;
}
