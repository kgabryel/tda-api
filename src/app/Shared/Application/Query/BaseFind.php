<?php

namespace App\Shared\Application\Query;

use InvalidArgumentException;

abstract class BaseFind implements Query
{
    protected array $ids;

    public function __construct(array $ids)
    {
        if ($ids === []) {
            throw new InvalidArgumentException(sprintf('Query "%s" must contain at least one id.', self::class));
        }
        $this->ids = $ids;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
