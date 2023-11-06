<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\FileId;

class FilesIdsList
{
    private array $ids;

    public function __construct(FileId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(FileId $fileId) => $fileId->getValue(), $this->ids);
    }
}
