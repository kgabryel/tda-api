<?php

namespace App\Catalog\Application\Command\RemoveVideo;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\VideoId;

/**
 * Odpina film od katalogu
 */
#[CommandHandler(RemoveVideoHandler::class)]
class RemoveVideo extends ModifyCatalogCommand
{
    private VideoId $videoId;

    public function __construct(CatalogId $id, VideoId $videoId)
    {
        parent::__construct($id);
        $this->videoId = $videoId;
    }

    public function getVideoId(): VideoId
    {
        return $this->videoId;
    }
}
