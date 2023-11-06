<?php

namespace App\Note\Application\Command\UpdateBackgroundColor;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\ValueObject\Hex;

/**
 * Aktualizuje kolor tla notatki
 */
#[CommandHandler(UpdateBackgroundColorHandler::class)]
class UpdateBackgroundColor extends ModifyNoteCommand
{
    private Hex $color;

    public function __construct(NoteId $id, Hex $color)
    {
        parent::__construct($id);
        $this->color = $color;
    }

    public function getColor(): Hex
    {
        return $this->color;
    }
}
