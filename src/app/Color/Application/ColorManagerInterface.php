<?php

namespace App\Color\Application;

use App\Color\Domain\Entity\Color;
use App\Color\Domain\Entity\ColorId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;

interface ColorManagerInterface
{
    public function delete(ColorId $colorId): void;

    public function create(string $name, Hex $colorValue, UserId $userId): Color;
}
