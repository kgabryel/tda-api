<?php

namespace App\Color\Application\Command\Delete;

use App\Color\Domain\Entity\ColorId;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;

/**
 * Usuwa kolor
 */
#[CommandHandler(DeleteHandler::class)]
class Delete implements Command
{
    private ColorId $colorId;

    public function __construct(ColorId $colorId)
    {
        $this->colorId = $colorId;
    }

    public function getColorId(): ColorId
    {
        return $this->colorId;
    }
}
