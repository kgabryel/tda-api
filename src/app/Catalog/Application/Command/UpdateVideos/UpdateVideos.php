<?php

namespace App\Catalog\Application\Command\UpdateVideos;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\VideoId;

/**
 * Aktualizuje liste filmy przypisanych do katalogu
 */
#[CommandHandler(UpdateVideosHandler::class)]
class UpdateVideos extends ModifyCatalogCommand
{
    private array $videos;

    public function __construct(CatalogId $id, VideoId ...$videos)
    {
        parent::__construct($id);
        $this->videos = $videos;
    }

    public function getVideos(): array
    {
        return $this->videos;
    }
}
