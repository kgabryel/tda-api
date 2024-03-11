<?php

namespace App\Color\Domain\Entity;

class Color
{
    private ColorId $colorId;
    private bool $deleted;

    public function __construct(ColorId $colorId)
    {
        $this->colorId = $colorId;
        $this->deleted = false;
    }

    public function getColorId(): ColorId
    {
        return $this->colorId;
    }

    public function delete(): bool
    {
        if ($this->deleted) {
            return false;
        }
        $this->deleted = true;

        return true;
    }
}
