<?php

namespace App\File\Infrastructure;

use App\File\Domain\Entity\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadService
{
    public function download(File $file): StreamedResponse
    {
        return Storage::download(
            $file->getFullPath(),
            $file->getOriginalName(),
            ['content-type' => $file->getMimeType()]
        );
    }
}
