<?php

namespace App\Video\Application\Query\Find;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\BaseFind;
use App\Shared\Domain\Entity\VideoId;

/**
 * Pobiera liste filmow na podstawie podanych id
 */
#[QueryHandler(FindQueryHandler::class)]
class Find extends BaseFind
{
    public function __construct(VideoId ...$ids)
    {
        parent::__construct($ids);
    }
}
