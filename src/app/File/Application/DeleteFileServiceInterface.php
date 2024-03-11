<?php

namespace App\File\Application;

interface DeleteFileServiceInterface
{
    public function delete(string $path): void;
}
