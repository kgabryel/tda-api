<?php

namespace App\Shared\Application\Dto;

class Ids
{
    private array $ids;

    public function __construct(mixed ...$ids)
    {
        $this->ids = $ids;
    }

    public function get(): array
    {
        return $this->ids;
    }

    public function isEmpty(): bool
    {
        return $this->ids === [];
    }
}
