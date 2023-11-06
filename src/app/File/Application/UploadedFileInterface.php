<?php

namespace App\File\Application;

interface UploadedFileInterface
{
    public function storeAs(string $path, string $fileName): void;

    public function getSize(): int;

    public function getParsedSize(): string;

    public function getMimeType(): string;

    public function getExtension(): string;

    public function getOriginalName(): string;
}
