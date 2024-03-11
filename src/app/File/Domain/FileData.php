<?php

namespace App\File\Domain;

interface FileData
{
    public function getSize(): int;

    public function getParsedSize(): string;

    public function getMimeType(): string;

    public function getExtension(): string;

    public function getOriginalName(): string;

    public function getPath(): string;
}
