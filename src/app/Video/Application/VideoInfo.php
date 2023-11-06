<?php

namespace App\Video\Application;

class VideoInfo
{
    private string $name;
    private string $id;

    public function __construct(string $id, string $name)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
