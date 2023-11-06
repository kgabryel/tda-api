<?php

namespace App\Task\Application\ViewModel;

use JsonSerializable;

class TaskStatus implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $color;
    private string $icon;

    public function __construct(int $id, string $name, string $color, string $icon)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
        $this->icon = $icon;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->icon
        ];
    }
}
