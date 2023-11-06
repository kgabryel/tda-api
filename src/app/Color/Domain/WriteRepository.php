<?php

namespace App\Color\Domain;

use App\Color\Domain\Entity\Color;
use App\Color\Domain\Entity\ColorId;
use App\Shared\Domain\Entity\UserId;

interface WriteRepository
{
    public function findById(ColorId $colorId, UserId $userId): Color;
}
