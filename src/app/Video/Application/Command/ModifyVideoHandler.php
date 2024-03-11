<?php

namespace App\Video\Application\Command;

use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\Query\FindById\FindById;
use App\Video\Domain\Entity\Video;

abstract class ModifyVideoHandler extends CommandHandler
{
    protected function getVideo(VideoId $videoId): Video
    {
        return $this->queryBus->handle(new FindById($videoId, QueryResult::DOMAIN_MODEL));
    }
}
