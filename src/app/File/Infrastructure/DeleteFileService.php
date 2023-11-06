<?php

namespace App\File\Infrastructure;

use App\File\Application\DeleteFileServiceInterface;
use Illuminate\Support\Facades\Storage;

class DeleteFileService implements DeleteFileServiceInterface
{
    public function delete(string $path): void
    {
        Storage::delete($path);
    }
}
