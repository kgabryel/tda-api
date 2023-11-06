<?php

namespace App\File\Infrastructure;

use App\File\Domain\FileData as FileDataInterface;
use ChrisUllyott\FileSize;

class FileData implements FileDataInterface
{
    private int $size;
    private string $mimeType;
    private string $extension;
    private string $originalName;
    private string $path;

    public function __construct(int $size, string $mimeType, string $extension, string $originalName, string $path)
    {
        $this->size = $size;
        $this->mimeType = $mimeType;
        $this->extension = $extension;
        $this->originalName = $originalName;
        $this->path = $path;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getParsedSize(): string
    {
        return (string)(new FileSize($this->size, 10));
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
