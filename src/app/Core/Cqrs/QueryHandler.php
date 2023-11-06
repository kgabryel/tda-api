<?php

namespace App\Core\Cqrs;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class QueryHandler
{
    private string $handlerName;

    public function __construct(string $handlerName)
    {
        $this->handlerName = $handlerName;
    }

    public function getHandlerName(): string
    {
        return $this->handlerName;
    }
}
