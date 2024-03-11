<?php

namespace App\Catalog\Domain\Event;

use App\File\Domain\Event\FilesModified;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\UserId;

/**
 * Podane pliku zostaly zmodyfikowane, zostaly odpiete lub przypiete do katalogu
 */
class FilesAssigmentChanged implements FilesModified
{
    private array $ids;
    private UserId $userId;

    public function __construct(UserId $userId, FileId ...$ids)
    {
        $this->ids = $ids;
        $this->userId = $userId;
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
