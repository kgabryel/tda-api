<?php

namespace App\Shared\Domain;

class SyncResult
{
    private array $attached;
    private array $detached;

    public function __construct(array $attached, array $detached)
    {
        $this->attached = $attached;
        $this->detached = $detached;
    }

    public function getAttached(): array
    {
        return $this->attached;
    }

    public function getDetached(): array
    {
        return $this->detached;
    }

    public function getAll(): array
    {
        return [...$this->attached, ...$this->detached];
    }
}
