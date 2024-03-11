<?php

namespace App\Shared\Application\Dto;

use App\Shared\Domain\Entity\NoteId;

class NotesIdsList
{
    private array $ids;

    public function __construct(NoteId ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function getIds(): array
    {
        return array_map(static fn(NoteId $noteId) => $noteId->getValue(), $this->ids);
    }
}
