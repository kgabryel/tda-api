<?php

namespace App\File\Application\Query\Find;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\BaseFind;
use App\Shared\Domain\Entity\FileId;

/**
 * Pobiera liste plikow na podstawie podanych id
 */
#[QueryHandler(FindQueryHandler::class)]
class Find extends BaseFind
{
    public function __construct(FileId ...$ids)
    {
        parent::__construct($ids);
    }
}
