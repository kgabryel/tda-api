<?php

namespace App\File\Infrastructure;

use App\Core\Cache;
use App\File\Domain\Entity\File;
use App\File\Domain\WriteRepository;
use App\File\Infrastructure\Model\File as FileModel;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

class FilesWriteRepository implements WriteRepository
{
    public function findById(FileId $fileId, UserId $userId): File
    {
        $fId = $fileId->getValue();
        $uId = $userId->getValue();

        return Cache::remember(FileManager::getCacheKey($fileId), static function () use ($fId, $uId) {
            return FileModel::where('id', '=', $fId)
                ->where('user_id', '=', $uId)
                ->firstOrFail()
                ->toDomainModel();
        });
    }
}
