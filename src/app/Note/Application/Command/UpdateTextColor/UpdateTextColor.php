<?php

namespace App\Note\Application\Command\UpdateTextColor;

use App\Core\Cqrs\CommandHandler;
use App\Note\Application\Command\ModifyNoteCommand;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\ValueObject\Hex;

/**
 * Aktualizuje kolor tekstu notatki
 */
#[CommandHandler(UpdateTextColorHandler::class)]
class UpdateTextColor extends ModifyNoteCommand
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
