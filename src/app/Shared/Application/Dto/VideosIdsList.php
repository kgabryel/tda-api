<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\VideoId;

class VideosIdsList
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
        return array_map(static fn(VideoId $videoId) => $videoId->getValue(), $this->ids);
    }
}
