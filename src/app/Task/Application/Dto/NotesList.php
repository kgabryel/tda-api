<?php

namespace App\Task\Application\Dto;

use App\Shared\Domain\Entity\NoteId;

class NotesList
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
        return array_map(static fn(NoteId $catalogId) => $catalogId->getValue(), $this->ids);
    }
}
