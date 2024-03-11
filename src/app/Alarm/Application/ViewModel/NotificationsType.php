<?php

namespace App\Alarm\Application\ViewModel;

use JsonSerializable;

class NotificationsType implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $color;

    public function __construct(int $id, string $name, string $color)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
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
