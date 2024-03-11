<?php

namespace App\Color\Domain\Event;

use App\Color\Domain\Entity\ColorId;
use App\Core\Cqrs\Event;

/**
 * Kolor zostal usuniety, nalezy usunac go z bazy danych
 */
class Deleted implements Event
{
    private ColorId $colorId;

    public function __construct(ColorId $colorId)
    {
        $this->colorId = $colorId;
    }

    public function getColorId(): ColorId
    {
        return $this->colorId;
    }
}
