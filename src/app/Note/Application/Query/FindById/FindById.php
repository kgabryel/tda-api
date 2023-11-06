<?php

namespace App\Note\Application\Query\FindById;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\NoteId;

/**
 * Pobiera notatke na podstawie id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private NoteId $noteId;
    private QueryResult $result;

    public function __construct(NoteId $noteId, QueryResult $result)
    {
        $this->noteId = $noteId;
        $this->result = $result;
    }

    public function getNoteId(): NoteId
    {
        return $this->noteId;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }
}
