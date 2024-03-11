<?php

namespace App\Task\Application\Dto;

use App\Shared\Domain\Entity\VideoId;

class VideosList
{
    private array $ids;

    public function __construct(VideoId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(VideoId $catalogId) => $catalogId->getValue(), $this->ids);
    }
}
