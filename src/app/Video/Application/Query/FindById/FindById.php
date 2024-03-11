<?php

namespace App\Video\Application\Query\FindById;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\VideoId;

/**
 * Pobiera film na podstawie id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private VideoId $videoId;
    private QueryResult $result;

    public function __construct(VideoId $videoId, QueryResult $result)
    {
        $this->videoId = $videoId;
        $this->result = $result;
    }

    public function getVideoId(): VideoId
    {
        return $this->videoId;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }
}
