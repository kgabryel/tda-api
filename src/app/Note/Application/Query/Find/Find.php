<?php

namespace App\Note\Application\Query\Find;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\BaseFind;
use App\Shared\Domain\Entity\NoteId;

/**
 * Pobiera liste notatek na podstawie podanych id
 */
#[QueryHandler(FindQueryHandler::class)]
class Find extends BaseFind
{
    public function __construct(NoteId ...$ids)
    {
        parent::__construct($ids);
    }
}
