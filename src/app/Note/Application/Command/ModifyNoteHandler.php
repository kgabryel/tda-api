<?php

namespace App\Note\Application\Command;

use App\Note\Application\Query\FindById\FindById;
use App\Note\Domain\Entity\Note;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\NoteId;

abstract class ModifyNoteHandler extends CommandHandler
{
    protected function getNote(NoteId $noteId): Note
    {
        return $this->queryBus->handle(new FindById($noteId, QueryResult::DOMAIN_MODEL));
    }
}
