<?php

namespace App\Shared\Infrastructure;

use App\Shared\Application\UuidInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid implements UuidInterface
{
    public function getValue(): string
    {
        return RamseyUuid::uuid4()->toString();
    }
}
