<?php

namespace App\File\Application\Command\UpdateName;

use App\Core\Cqrs\CommandHandler;
use App\File\Application\Command\ModifyFileCommand;
use App\Shared\Domain\Entity\FileId;

/**
 * Aktualizuje nazwe pliku
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifyFileCommand
{
    private string $name;

    public function __construct(FileId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
