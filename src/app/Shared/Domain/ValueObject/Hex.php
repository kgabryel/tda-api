<?php

namespace App\Shared\Domain\ValueObject;

class Hex
{
    private string $color;

    public function __construct(string $color)
    {
        $this->color = $color;
    }

    public function isSame(self $color): bool
    {
        return $this->color === $color->getColor();
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
