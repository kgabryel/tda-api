<?php

namespace App\Color\Application\Command\Create;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\ValueObject\Hex;

/**
 * Tworzy nowy kolor
 */
#[CommandHandler(CreateHandler::class)]
class Create implements Command
{
    private string $name;
    private Hex $color;

    public function __construct(string $name, Hex $color)
    {
        $this->name = $name;
        $this->color = $color;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): Hex
    {
        return $this->color;
    }
}
