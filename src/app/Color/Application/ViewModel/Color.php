<?php

namespace App\Color\Application\ViewModel;

use JsonSerializable;

class Color implements JsonSerializable
{
    private int $id;
    private string $color;
    private string $name;

    public function __construct(int $id, string $name, string $color)
    {
        $this->id = $id;
        $this->color = $color;
        $this->name = $name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color
        ];
    }
}
