<?php

namespace App\Shared\Domain\Entity;

use Stringable;

abstract class StringId implements Stringable
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getValue(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
