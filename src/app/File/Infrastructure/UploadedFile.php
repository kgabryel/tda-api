<?php

namespace App\File\Infrastructure;

use App\File\Application\UploadedFileInterface;
use App\File\Domain\FileData;
use ChrisUllyott\FileSize;
use Illuminate\Http\UploadedFile as File;

class UploadedFile implements UploadedFileInterface, FileData
{
    private File $file;
    private string $path;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function storeAs(string $path, string $fileName): void
    {
        $this->path = $fileName;
        $this->file->storeAs($path, $fileName);
    }

    public function getParsedSize(): string
    {
        return (string)(new FileSize($this->file->getSize(), 10));
    }

    public function getSize(): int
    {
        return $this->file->getSize();
    }

    public function getMimeType(): string
    {
        return $this->file->getClientMimeType();
    }

    public function getExtension(): string
    {
        return $this->file->getClientOriginalExtension();
    }

    public function getOriginalName(): string
    {
        return $this->file->getClientOriginalName();
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
