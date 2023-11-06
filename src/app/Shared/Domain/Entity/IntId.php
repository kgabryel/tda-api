<?php

namespace App\Shared\Domain\Entity;

use Stringable;

abstract class IntId implements Stringable
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getValue(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }
}
